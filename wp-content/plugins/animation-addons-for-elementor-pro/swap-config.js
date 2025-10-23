const fs = require('fs');
const path = require('path');

const configName = process.argv[2];

if (!configName || (configName !== 'animBuilder' && configName !== 'cptBuilder')) {
  console.error('Please specify either "animBuilder" or "cptBuilder"');
  process.exit(1);
}

const sourcePath = path.join(__dirname, `components.${configName}.json`);
const destPath = path.join(__dirname, 'components.json');

fs.copyFileSync(sourcePath, destPath);
console.log(`Swapped to ${configName} configuration`);