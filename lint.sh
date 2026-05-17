#!/bin/bash
# ============================================================
# PHP Lint CI — Lotería v1.1
# Ejecuta php -l (lint) sobre todos los archivos PHP del proyecto
# ============================================================

PASS=0
FAIL=0
FILES=()

echo "======================================"
echo "  PHP LINT — Verificación de sintaxis"
echo "======================================"
echo ""

for f in *.php; do
    result=$(php -l "$f" 2>&1)
    if echo "$result" | grep -q "No syntax errors"; then
        echo "  ✅  $f — OK"
        PASS=$((PASS + 1))
    else
        echo "  ❌  $f — ERROR:"
        echo "       $result"
        FAIL=$((FAIL + 1))
        FILES+=("$f")
    fi
done

echo ""
echo "--------------------------------------"
echo "  Resultado: $PASS OK  |  $FAIL ERRORES"
echo "--------------------------------------"

if [ $FAIL -gt 0 ]; then
    echo "  Archivos con errores: ${FILES[*]}"
    exit 1
else
    echo "  ✅ Todos los archivos pasan el lint."
    exit 0
fi
