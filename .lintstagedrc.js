export default {
  "**/*.php": [
    "vendor/bin/mago analyze",
    "vendor/bin/mago lint",
    "vendor/bin/mago format",
  ],
};
