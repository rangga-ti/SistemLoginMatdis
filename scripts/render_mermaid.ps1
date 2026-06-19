# PowerShell script to render Mermaid diagram using npx
$input = 'docs/decision_tree.mmd'
$outpng = 'docs/decision_tree.png'
$outsvg = 'docs/decision_tree.svg'

if (-not (Get-Command npx -ErrorAction SilentlyContinue)) {
    Write-Error 'npx not found. Install Node.js/npm first.'
    exit 1
}

npx @mermaid-js/mermaid-cli -i $input -o $outpng
if ($LASTEXITCODE -ne 0) { Write-Error "Failed to render PNG"; exit 2 }
Write-Output "Rendered $outpng"

npx @mermaid-js/mermaid-cli -i $input -o $outsvg
if ($LASTEXITCODE -ne 0) { Write-Error "Failed to render SVG"; exit 3 }
Write-Output "Rendered $outsvg"

exit 0
