#!/usr/bin/env bash
set -e

echo "==> 1. Menjalankan PHPUnit & Memperbarui Laporan JUnit..."
docker compose exec -T app vendor/bin/phpunit --testdox --log-junit tests/phpunit-report.xml

echo "==> 2. Menjalankan SonarScanner CLI ke SonarQube Lokal..."
docker run --rm \
  -v "$(pwd):/usr/src" \
  sonarsource/sonar-scanner-cli \
  -Dsonar.host.url="http://host.docker.internal:9000" \
  -Dsonar.login="squ_4e70ee77636ea9822b8aae859c27628fa5eaf7af"

echo ""
echo "=========================================================="
echo "✅ Pemindaian SonarQube selesai!"
echo "👉 Buka Dashboard: http://localhost:9000/dashboard?id=saukurtask"
echo "=========================================================="
