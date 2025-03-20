const express = require("express");
const mongoose = require("mongoose");
const dotenv = require("dotenv");
const cors = require("cors");
const bodyParser = require("body-parser");

// Load environment variables
dotenv.config();

const app = express();
app.use(cors());
app.use(bodyParser.json());

// Import Routes
const authroutes = require("./route/auth");
const adminroutes = require("./routes/admin");
const subscriptionroutes = require("./routes/subscription");
const profileroutes = require("./routes/profile");

// Use Routes
app.use("/api/auth", authroutes);
app.use("/api/admin", adminroutes);
app.use("/api/subscription", subscriptionroutes);
app.use("/api/profile", profileroutes);

// Connect to MongoDB
mongoose.connect(process.env.MONGO_URI, { useNewUrlParser: true, useUnifiedTopology: true })
  .then(() => console.log("MongoDB connected"))
  .catch(err => console.log(err));

const PORT = process.env.PORT || 5000;
app.listen(PORT, () => console.log(`Server running on port ${PORT}`));
