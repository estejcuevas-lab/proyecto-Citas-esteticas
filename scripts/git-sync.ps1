param(
    [Parameter(Mandatory = $true)]
    [string]$Message
)

$ErrorActionPreference = "Stop"

Write-Host ""
Write-Host "Revisando estado del repositorio..." -ForegroundColor Cyan
git status --short --branch

Write-Host ""
Write-Host "Agregando cambios..." -ForegroundColor Cyan
git add .

$hasChanges = git diff --cached --name-only

if (-not $hasChanges) {
    Write-Host "No hay cambios nuevos para guardar." -ForegroundColor Yellow
    exit 0
}

Write-Host ""
Write-Host "Creando commit..." -ForegroundColor Cyan
git commit -m $Message

Write-Host ""
Write-Host "Subiendo cambios a GitHub..." -ForegroundColor Cyan
git push

Write-Host ""
Write-Host "Proceso completado." -ForegroundColor Green
