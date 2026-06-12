#!/bin/bash
# =============================================================
# deploy.sh — Script de deploy do Festas Lulinas no VPS
# Uso: bash deploy.sh
# =============================================================

set -e  # Para na primeira falha

# --- Configurações -------------------------------------------
SITE_PATH="$(pwd)"
PHP="php"
BRANCH="main"

# --- Cores para output ---------------------------------------
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo ""
echo -e "${YELLOW}========================================${NC}"
echo -e "${YELLOW}  DEPLOY — Festas Lulinas${NC}"
echo -e "${YELLOW}========================================${NC}"
echo ""

# 1. Confirma diretório atual
echo -e "${YELLOW}[1/6] Diretório atual...${NC}"
echo -e "${GREEN}OK: $SITE_PATH${NC}"

# 2. Puxa as atualizações do GitHub
echo ""
echo -e "${YELLOW}[2/6] Baixando atualizações do GitHub...${NC}"
git fetch origin
git reset --hard origin/$BRANCH
git pull origin $BRANCH
echo -e "${GREEN}OK: Código atualizado.${NC}"

# 3. Limpa os caches do CodeIgniter
echo ""
echo -e "${YELLOW}[3/6] Limpando caches...${NC}"
$PHP spark cache:clear 2>/dev/null || true
rm -rf writable/cache/* 2>/dev/null || true
echo -e "${GREEN}OK: Cache limpo.${NC}"

# 4. Roda as migrations pendentes
echo ""
echo -e "${YELLOW}[4/6] Rodando migrations...${NC}"
$PHP spark migrate --all
echo -e "${GREEN}OK: Migrations aplicadas.${NC}"

# 5. Ajusta permissões das pastas de escrita
echo ""
echo -e "${YELLOW}[5/6] Ajustando permissões...${NC}"
chmod -R 775 writable/
chmod -R 775 public/uploads/ 2>/dev/null || true
chmod -R 775 public/assets/ 2>/dev/null || true
echo -e "${GREEN}OK: Permissões ajustadas.${NC}"

# 6. Verifica a aplicação
echo ""
echo -e "${YELLOW}[6/6] Verificando aplicação...${NC}"
$PHP spark env 2>/dev/null | grep -E "Environment|baseURL" || true

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}  DEPLOY CONCLUIDO COM SUCESSO!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "Site: https://festaslulinas.com.br"
echo ""
