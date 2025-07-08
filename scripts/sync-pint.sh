#!/bin/bash

set -e

REMOTE_URL="https://raw.githubusercontent.com/Casa-Publicadora-Brasileira/portal-utils/master/pint.json"
LOCAL_FILE="pint.json"

if [ ! -f "$LOCAL_FILE" ]; then
    echo "📄 Arquivo $LOCAL_FILE não encontrado. Criando novo a partir do repositório central."
    curl -s "$REMOTE_URL" -o "$LOCAL_FILE"
    echo "✅ Arquivo criado."
    exit 0
fi

REMOTE_CONTENT=$(curl -s "$REMOTE_URL")
LOCAL_CONTENT=$(cat "$LOCAL_FILE")

if diff -q <(echo "$REMOTE_CONTENT") <(echo "$LOCAL_CONTENT") > /dev/null; then
    echo "✅ O pint.json já está atualizado."
else
    echo "🔄 O pint.json está desatualizado. Atualizando automaticamente..."
    echo "$REMOTE_CONTENT" > "$LOCAL_FILE"
    echo "✅ Atualizado com sucesso."
fi
