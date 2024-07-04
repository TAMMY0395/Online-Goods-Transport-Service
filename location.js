// server.js

const express = require('express');
const http = require('http');
const socketIo = require('socket.io');
const mysql = require('mysql');
const bodyParser = require('body-parser');

const app = express();
const server = http.createServer(app);
const io = socketIo(server);

// MySQL setup
const db = mysql.createConnection({
  host: 'localhost',
  user: 'root', // MySQL username (default is 'root')
  password: '', // MySQL password (default is blank)
  database: 'vehicle_tracking' // Your MySQL database name
});

db.connect((err) => {
  if (err) {
    console.error('MySQL connection error:', err);
    return;
  }
  console.log('Connected to MySQL database');
});

// Middleware
app.use(bodyParser.json());

// API routes

// Update vehicle location
app.post('/update-location', (req, res) => {
  const { trackingCode, latitude, longitude } = req.body;
  const sql = `UPDATE vehicles SET latitude = ${latitude}, longitude = ${longitude} WHERE tracking_code = '${trackingCode}'`;
  db.query(sql, (err, result) => {
    if (err) {
      console.error('Error updating location:', err);
      res.status(500).json({ message: 'Internal server error' });
      return;
    }
    io.emit('locationUpdate', { trackingCode, latitude, longitude });
    res.status(200).json({ message: 'Location updated successfully' });
  });
});

// Frontend serving
app.use(express.static(__dirname + '/public'));

// Socket.IO handling
io.on('connection', (socket) => {
  console.log('Client connected');
});

// Start server
const PORT = process.env.PORT || 3000;
server.listen(PORT, () => {
  console.log(`Server running on port ${PORT}`);
});
