const http = require('http');
const fs = require('fs');
const path = require('path');

const PORT = 5000;

const server = http.createServer((req, res) => {
  // Serve info.html for root
  if (req.url === '/' || req.url === '/index.html') {
    fs.readFile(path.join(__dirname, 'info.html'), (err, data) => {
      if (err) {
        res.writeHead(500);
        res.end('Error loading info page');
        return;
      }
      res.writeHead(200, { 'Content-Type': 'text/html' });
      res.end(data);
    });
  } else {
    res.writeHead(404);
    res.end('Not found');
  }
});

server.listen(PORT, '0.0.0.0', () => {
  console.log(`\n=================================`);
  console.log(`🌙 Echoes & Brews - Deployment Info`);
  console.log(`=================================`);
  console.log(`\nServer running at http://0.0.0.0:${PORT}`);
  console.log(`\n✓ Project Status: Complete & Ready`);
  console.log(`\nThis PHP application is ready for deployment to InfinityFree.`);
  console.log(`See QUICKSTART.md and DEPLOYMENT.md for instructions.\n`);
});
