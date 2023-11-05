require('dotenv').config();
var express = require('express');
var mongoose = require('mongoose');
 


/// for atlas
//mongodb+srv://kishan:111111111@cluster0-t6mie.mongodb.net/edutech?retryWrites=true&w=majority
//mongodb+srv://xcellinsprocare:111111111@cluster0.i0xczes.mongodb.net/edutech?retryWrites=true&w=majority
mongoose.connect(process.env.MONGO_URL , { useUnifiedTopology: true, useNewUrlParser: true });
mongoose.connection.on('connected',()=>{
  console.log('connected to db');
});

mongoose.connection.on('error',(err)=>{

  if(err){
    console.log('Error in db connection '+err );
  }
 
});
/// for atla end


/// for hosting raja

// mongoose.connect('mongodb://vps_contestuser:vps_contestuser@127.0.0.1:27017/vps_contest', { useUnifiedTopology: true, useNewUrlParser: true });

 
// var db = mongoose.connection;
 
// db.on('error', console.error.bind(console, 'connection error:'));
 
// db.once('open', function() {
//   console.log("Connection Successful!");
  
   
// }); 

/// for hosting raja end
 


var bodyParser = require('body-parser');  
var cors = require('cors');
const route = require('./router/route');
 
  
var path = require('path');

var app = express();

app.use(cors());

app.use(bodyParser.json({limit: '10mb', extended: true}))
app.use(bodyParser.urlencoded({limit: '10mb', extended: true}))
app.use('/apipolicy',route);
 
 app.use( express.static(path.join(__dirname,'public')));

app.get('/', function(req, res){
    console.log('called me ');
   res.send("Hello world error!!" ); 
});
const Port=process.env.Port || 7500;
app.listen(Port,()=>{
  console.log('Listening '+Port);
});

