#!/bin/bash

# Install dependencies
npm install

# Build assets
npm run build

# Copy built assets to public directory
cp -r public/build/* public/ 2>/dev/null || true

echo "Build completed successfully!"
