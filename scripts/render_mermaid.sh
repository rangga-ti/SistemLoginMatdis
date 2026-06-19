#!/usr/bin/env bash
# Render Mermaid diagram to PNG/SVG using mermaid-cli (mmdc)
# Requires Node.js and npm. Run: chmod +x scripts/render_mermaid.sh

if ! command -v npx >/dev/null 2>&1; then
  echo "npx not found. Install Node.js/npm first."
  exit 1
fi

INPUT=docs/decision_tree.mmd
OUTPNG=docs/decision_tree.png
OUTSVG=docs/decision_tree.svg

npx @mermaid-js/mermaid-cli -i "$INPUT" -o "$OUTPNG"
if [ $? -ne 0 ]; then
  echo "Failed to render PNG."
  exit 2
fi

echo "Rendered $OUTPNG"

npx @mermaid-js/mermaid-cli -i "$INPUT" -o "$OUTSVG"
if [ $? -ne 0 ]; then
  echo "Failed to render SVG."
  exit 3
fi

echo "Rendered $OUTSVG"

exit 0
