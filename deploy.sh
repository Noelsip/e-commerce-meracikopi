#!/bin/bash
# ============================================================================
#  Meracikopi E-Commerce - Production Deployment Script
#  Usage:
#    ./deploy.sh              → Deploy latest from current branch
#    ./deploy.sh --rollback   → Rollback to previous deployment
#    ./deploy.sh --status     → Show current deployment status
#    ./deploy.sh --logs       → Tail application logs
#    ./deploy.sh --fresh      → Fresh deployment (rebuild everything from scratch)
# ============================================================================

set -euo pipefail

# ─── Configuration ──────────────────────────────────────────────────────────
APP_NAME="meracikopi"
PROJECT_DIR="$(cd "$(dirname "$0")" && pwd)"
COMPOSE_FILE="${PROJECT_DIR}/docker-compose.yml"
ENV_FILE="${PROJECT_DIR}/.env.docker"
BACKUP_DIR="${PROJECT_DIR}/backups"
LOG_FILE="${PROJECT_DIR}/deploy.log"
TIMESTAMP=$(date +"%Y%m%d_%H%M%S")
GIT_BRANCH="${GIT_BRANCH:-main}"

# Container names (match docker-compose.yml)
APP_CONTAINER="meracikopi-app"
DB_CONTAINER="meracikopi-db"
REDIS_CONTAINER="meracikopi-redis"

# ─── Colors ─────────────────────────────────────────────────────────────────
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
BOLD='\033[1m'
NC='\033[0m' # No Color

# ─── Utility Functions ──────────────────────────────────────────────────────

log() {
    local level="$1"
    shift
    local message="$*"
    local timestamp=$(date +"%Y-%m-%d %H:%M:%S")
    echo -e "${timestamp} [${level}] ${message}" >> "${LOG_FILE}"

    case "$level" in
        INFO)    echo -e "${CYAN}[ℹ]${NC} ${message}" ;;
        SUCCESS) echo -e "${GREEN}[✔]${NC} ${message}" ;;
        WARN)    echo -e "${YELLOW}[⚠]${NC} ${message}" ;;
        ERROR)   echo -e "${RED}[✖]${NC} ${message}" ;;
        STEP)    echo -e "${BLUE}${BOLD}[→]${NC} ${BOLD}${message}${NC}" ;;
    esac
}

separator() {
    echo -e "${BLUE}─────────────────────────────────────────────────────────${NC}"
}

header() {
    echo ""
    separator
    echo -e "${BOLD}${CYAN}  ☕ Meracikopi Deployment Script${NC}"
    echo -e "${BOLD}${CYAN}  $(date +"%Y-%m-%d %H:%M:%S")${NC}"
    separator
    echo ""
}

check_requirements() {
    log "STEP" "Checking requirements..."

    local missing=()

    if ! command -v docker &> /dev/null; then
        missing+=("docker")
    fi

    if ! command -v docker compose &> /dev/null && ! command -v docker-compose &> /dev/null; then
        missing+=("docker-compose")
    fi

    if ! command -v git &> /dev/null; then
        missing+=("git")
    fi

    if [ ${#missing[@]} -gt 0 ]; then
        log "ERROR" "Missing required tools: ${missing[*]}"
        log "ERROR" "Please install them before running this script."
        exit 1
    fi

    log "SUCCESS" "All requirements met."
}

# Determine docker compose command (v2 plugin vs standalone)
get_compose_cmd() {
    if docker compose version &> /dev/null; then
        echo "docker compose"
    elif command -v docker-compose &> /dev/null; then
        echo "docker-compose"
    else
        log "ERROR" "Docker Compose not found!"
        exit 1
    fi
}

COMPOSE_CMD=""

# ─── Pre-flight Checks ─────────────────────────────────────────────────────

preflight_checks() {
    log "STEP" "Running pre-flight checks..."

    # Check if .env.docker exists
    if [ ! -f "${ENV_FILE}" ]; then
        log "ERROR" ".env.docker not found. Please create it from .env.example first."
        log "INFO" "  cp .env.example .env.docker && nano .env.docker"
        exit 1
    fi

    # Check if docker daemon is running
    if ! docker info &> /dev/null 2>&1; then
        log "ERROR" "Docker daemon is not running. Please start Docker first."
        exit 1
    fi

    # Check APP_KEY is set
    if ! grep -q "APP_KEY=base64:" "${ENV_FILE}" 2>/dev/null; then
        log "WARN" "APP_KEY not set in .env.docker"
        log "INFO" "  A key will be auto-generated on first boot."
    fi

    log "SUCCESS" "Pre-flight checks passed."
}

# ─── Git Operations ─────────────────────────────────────────────────────────

pull_latest() {
    log "STEP" "Pulling latest code from git (branch: ${GIT_BRANCH})..."

    cd "${PROJECT_DIR}"

    # Save current commit for rollback
    local current_commit=$(git rev-parse HEAD 2>/dev/null || echo "unknown")
    echo "${current_commit}" > "${PROJECT_DIR}/.last_deploy_commit"

    # Check for uncommitted changes
    if ! git diff-index --quiet HEAD -- 2>/dev/null; then
        log "WARN" "Uncommitted changes detected. Stashing..."
        git stash --include-untracked
    fi

    # Pull latest
    git fetch origin "${GIT_BRANCH}"
    git checkout "${GIT_BRANCH}"
    git pull origin "${GIT_BRANCH}"

    local new_commit=$(git rev-parse --short HEAD)
    local commit_msg=$(git log -1 --pretty=format:'%s')
    log "SUCCESS" "Updated to commit: ${new_commit} - ${commit_msg}"
}

# ─── Database Backup ────────────────────────────────────────────────────────

backup_database() {
    log "STEP" "Backing up database..."

    mkdir -p "${BACKUP_DIR}"

    # Check if database container is running
    if docker ps --format '{{.Names}}' | grep -q "^${DB_CONTAINER}$"; then
        local backup_file="${BACKUP_DIR}/db_backup_${TIMESTAMP}.sql.gz"
        
        # Source DB credentials from env file
        local db_name=$(grep "^DB_DATABASE=" "${ENV_FILE}" | cut -d '=' -f2)
        local db_user=$(grep "^DB_USERNAME=" "${ENV_FILE}" | cut -d '=' -f2)
        local db_pass=$(grep "^DB_PASSWORD=" "${ENV_FILE}" | cut -d '=' -f2)

        docker exec "${DB_CONTAINER}" mysqldump \
            -u"${db_user}" \
            -p"${db_pass}" \
            "${db_name}" 2>/dev/null | gzip > "${backup_file}"

        if [ -s "${backup_file}" ]; then
            local size=$(du -h "${backup_file}" | cut -f1)
            log "SUCCESS" "Database backed up: ${backup_file} (${size})"
        else
            log "WARN" "Database backup is empty (may be first deployment)"
            rm -f "${backup_file}"
        fi

        # Cleanup old backups (keep last 5)
        cd "${BACKUP_DIR}"
        ls -t db_backup_*.sql.gz 2>/dev/null | tail -n +6 | xargs -r rm -f
        cd "${PROJECT_DIR}"
    else
        log "INFO" "Database container not running, skipping backup."
    fi
}

# ─── Build & Deploy ─────────────────────────────────────────────────────────

build_and_deploy() {
    log "STEP" "Building and deploying containers..."

    cd "${PROJECT_DIR}"

    # Build the app image with no cache for clean build
    log "INFO" "Building Docker image (this may take a few minutes)..."
    ${COMPOSE_CMD} -f "${COMPOSE_FILE}" build --no-cache app

    # Stop old containers and start new ones
    log "INFO" "Starting containers..."
    ${COMPOSE_CMD} -f "${COMPOSE_FILE}" up -d

    log "SUCCESS" "Containers started successfully."
}

build_and_deploy_fresh() {
    log "STEP" "Fresh deployment - rebuilding everything from scratch..."

    cd "${PROJECT_DIR}"

    # Stop and remove all containers, networks, and volumes (except data volumes)
    log "WARN" "Stopping and removing all containers..."
    ${COMPOSE_CMD} -f "${COMPOSE_FILE}" down --remove-orphans

    # Rebuild all images
    log "INFO" "Rebuilding all Docker images from scratch..."
    ${COMPOSE_CMD} -f "${COMPOSE_FILE}" build --no-cache

    # Start all containers
    log "INFO" "Starting all containers..."
    ${COMPOSE_CMD} -f "${COMPOSE_FILE}" up -d

    log "SUCCESS" "Fresh deployment completed."
}

# ─── Health Check ────────────────────────────────────────────────────────────

wait_for_healthy() {
    log "STEP" "Waiting for application to become healthy..."

    local max_wait=120 # seconds
    local waited=0
    local interval=5

    while [ $waited -lt $max_wait ]; do
        # Check if app container is running
        if ! docker ps --format '{{.Names}}' | grep -q "^${APP_CONTAINER}$"; then
            log "WARN" "App container not running yet, waiting..."
            sleep $interval
            waited=$((waited + interval))
            continue
        fi

        # Check health endpoint
        local http_code=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/health 2>/dev/null || echo "000")

        if [ "$http_code" = "200" ]; then
            log "SUCCESS" "Application is healthy! (HTTP ${http_code})"
            return 0
        fi

        echo -ne "\r  Waiting... ${waited}s / ${max_wait}s (HTTP: ${http_code})"
        sleep $interval
        waited=$((waited + interval))
    done

    echo ""
    log "ERROR" "Application did not become healthy within ${max_wait}s"
    log "INFO" "Check logs with: ./deploy.sh --logs"
    return 1
}

# ─── Post-Deploy Optimization ───────────────────────────────────────────────

post_deploy() {
    log "STEP" "Running post-deployment tasks..."

    # Wait for container to be ready
    sleep 5

    # Run migrations (already handled by entrypoint.sh, but run again to be safe)
    log "INFO" "Running database migrations..."
    docker exec "${APP_CONTAINER}" php artisan migrate --force 2>/dev/null || \
        log "WARN" "Migration command returned non-zero (may already be up to date)"

    # Optimize Laravel for production
    log "INFO" "Optimizing Laravel..."
    docker exec "${APP_CONTAINER}" php artisan config:cache 2>/dev/null || true
    docker exec "${APP_CONTAINER}" php artisan route:cache 2>/dev/null || true
    docker exec "${APP_CONTAINER}" php artisan view:cache 2>/dev/null || true
    docker exec "${APP_CONTAINER}" php artisan event:cache 2>/dev/null || true

    # Ensure storage link
    docker exec "${APP_CONTAINER}" php artisan storage:link --force 2>/dev/null || true

    # Restart queue workers to pick up new code
    log "INFO" "Restarting queue workers..."
    docker exec "${APP_CONTAINER}" php artisan queue:restart 2>/dev/null || true

    log "SUCCESS" "Post-deployment tasks completed."
}

# ─── Status ──────────────────────────────────────────────────────────────────

show_status() {
    header
    log "STEP" "Current Deployment Status"
    echo ""

    # Git info
    cd "${PROJECT_DIR}"
    local branch=$(git rev-parse --abbrev-ref HEAD 2>/dev/null || echo "unknown")
    local commit=$(git rev-parse --short HEAD 2>/dev/null || echo "unknown")
    local commit_msg=$(git log -1 --pretty=format:'%s' 2>/dev/null || echo "unknown")
    local commit_date=$(git log -1 --pretty=format:'%ci' 2>/dev/null || echo "unknown")

    echo -e "  ${BOLD}Git Branch:${NC}  ${branch}"
    echo -e "  ${BOLD}Commit:${NC}      ${commit} - ${commit_msg}"
    echo -e "  ${BOLD}Date:${NC}        ${commit_date}"
    echo ""

    # Container status
    echo -e "  ${BOLD}Container Status:${NC}"
    ${COMPOSE_CMD} -f "${COMPOSE_FILE}" ps --format "table {{.Name}}\t{{.Status}}\t{{.Ports}}" 2>/dev/null || \
        ${COMPOSE_CMD} -f "${COMPOSE_FILE}" ps
    echo ""

    # Disk usage
    echo -e "  ${BOLD}Docker Disk Usage:${NC}"
    docker system df 2>/dev/null | head -5
    echo ""

    # Health check
    local http_code=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8000/health 2>/dev/null || echo "000")
    if [ "$http_code" = "200" ]; then
        echo -e "  ${BOLD}Health:${NC}      ${GREEN}✔ Healthy (HTTP ${http_code})${NC}"
    else
        echo -e "  ${BOLD}Health:${NC}      ${RED}✖ Unhealthy (HTTP ${http_code})${NC}"
    fi
    echo ""
}

# ─── Logs ────────────────────────────────────────────────────────────────────

show_logs() {
    log "STEP" "Tailing application logs (Ctrl+C to exit)..."
    ${COMPOSE_CMD} -f "${COMPOSE_FILE}" logs -f --tail=100 app
}

# ─── Rollback ────────────────────────────────────────────────────────────────

rollback() {
    header
    log "STEP" "Rolling back to previous deployment..."

    cd "${PROJECT_DIR}"

    # Check if rollback commit exists
    if [ ! -f "${PROJECT_DIR}/.last_deploy_commit" ]; then
        log "ERROR" "No previous deployment found to rollback to."
        exit 1
    fi

    local rollback_commit=$(cat "${PROJECT_DIR}/.last_deploy_commit")
    
    if [ "$rollback_commit" = "unknown" ]; then
        log "ERROR" "Previous commit hash is unknown, cannot rollback."
        exit 1
    fi

    log "INFO" "Rolling back to commit: ${rollback_commit}"

    # Checkout the previous commit
    git checkout "${rollback_commit}"

    # Rebuild and restart
    ${COMPOSE_CMD} -f "${COMPOSE_FILE}" build --no-cache app
    ${COMPOSE_CMD} -f "${COMPOSE_FILE}" up -d

    # Wait for healthy
    wait_for_healthy

    # Run post-deploy
    post_deploy

    log "SUCCESS" "Rollback completed successfully!"
    log "WARN" "You are now in detached HEAD state. To return to a branch, run:"
    log "INFO" "  git checkout ${GIT_BRANCH}"
}

# ─── Cleanup ─────────────────────────────────────────────────────────────────

cleanup_docker() {
    log "STEP" "Cleaning up unused Docker resources..."
    docker image prune -f
    docker builder prune -f
    log "SUCCESS" "Docker cleanup completed."
}

# ─── Main Deploy Flow ───────────────────────────────────────────────────────

deploy() {
    local start_time=$(date +%s)

    header

    check_requirements
    COMPOSE_CMD=$(get_compose_cmd)
    preflight_checks
    pull_latest
    backup_database
    build_and_deploy
    wait_for_healthy
    post_deploy
    cleanup_docker

    local end_time=$(date +%s)
    local duration=$((end_time - start_time))

    separator
    echo ""
    log "SUCCESS" "🎉 Deployment completed successfully in ${duration}s!"
    echo ""
    echo -e "  ${BOLD}App URL:${NC}         http://localhost:8000"
    echo -e "  ${BOLD}phpMyAdmin:${NC}      http://localhost:8080"
    echo -e "  ${BOLD}Status:${NC}          ./deploy.sh --status"
    echo -e "  ${BOLD}Logs:${NC}            ./deploy.sh --logs"
    echo -e "  ${BOLD}Rollback:${NC}        ./deploy.sh --rollback"
    echo ""
    separator
}

deploy_fresh() {
    local start_time=$(date +%s)

    header

    check_requirements
    COMPOSE_CMD=$(get_compose_cmd)
    preflight_checks
    pull_latest
    build_and_deploy_fresh
    wait_for_healthy
    post_deploy
    cleanup_docker

    local end_time=$(date +%s)
    local duration=$((end_time - start_time))

    separator
    echo ""
    log "SUCCESS" "🎉 Fresh deployment completed successfully in ${duration}s!"
    echo ""
    echo -e "  ${BOLD}App URL:${NC}         http://localhost:8000"
    echo -e "  ${BOLD}phpMyAdmin:${NC}      http://localhost:8080"
    echo -e "  ${BOLD}Status:${NC}          ./deploy.sh --status"
    echo -e "  ${BOLD}Logs:${NC}            ./deploy.sh --logs"
    echo ""
    separator
}

# ─── Entry Point ─────────────────────────────────────────────────────────────

main() {
    # Ensure we're in the project directory
    cd "${PROJECT_DIR}"

    # Initialize compose command
    COMPOSE_CMD=$(get_compose_cmd)

    case "${1:-}" in
        --rollback|-r)
            rollback
            ;;
        --status|-s)
            show_status
            ;;
        --logs|-l)
            show_logs
            ;;
        --fresh|-f)
            echo -e "${YELLOW}⚠ Fresh deployment will rebuild ALL containers (data volumes preserved).${NC}"
            echo -e "${YELLOW}  Are you sure? (y/N)${NC}"
            read -r confirm
            if [ "$confirm" = "y" ] || [ "$confirm" = "Y" ]; then
                deploy_fresh
            else
                log "INFO" "Fresh deployment cancelled."
            fi
            ;;
        --help|-h)
            echo ""
            echo "Usage: ./deploy.sh [OPTION]"
            echo ""
            echo "Options:"
            echo "  (no args)       Deploy latest from git (default)"
            echo "  --fresh,   -f   Fresh deployment (rebuild everything)"
            echo "  --rollback,-r   Rollback to previous deployment"
            echo "  --status,  -s   Show current deployment status"
            echo "  --logs,    -l   Tail application logs"
            echo "  --help,    -h   Show this help message"
            echo ""
            echo "Environment Variables:"
            echo "  GIT_BRANCH      Git branch to deploy (default: main)"
            echo ""
            echo "Examples:"
            echo "  ./deploy.sh                    # Deploy latest from 'main'"
            echo "  GIT_BRANCH=staging ./deploy.sh # Deploy from 'staging' branch"
            echo "  ./deploy.sh --status           # Check deployment status"
            echo "  ./deploy.sh --rollback         # Rollback to previous deploy"
            echo ""
            ;;
        "")
            deploy
            ;;
        *)
            log "ERROR" "Unknown option: $1"
            log "INFO" "Run ./deploy.sh --help for usage information."
            exit 1
            ;;
    esac
}

main "$@"
