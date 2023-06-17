var mongoose = require('mongoose'); 

const agentSchema = mongoose.Schema({
    agent_id_int: {
        type: Number,

    },
    customer_id_int: {
        type: Number,

    },
     
    agent_added_by_id_int: {
        type: Number,

    },
     name: {
        type: String,

    },
    alt_mobile: {
        type: String,

    },
     
    email: {
        type: String,

    },
    mobile: {
        type: String,

    },
    state: {
        type: String,

    },
    city: {
        type: String,

    },
    pincode: {
        type: String,

    },
    address: {
        type: String,

    },
    working_for_user_or_agent: {
        type: String,

    },
    imageArr: {
        type: Array, 
    }, 
    dob: {
        type: String,

    },
    pancard:{
        type: String,

    },
    aadhaar:{
        type: String,

    },
    licenseArr: {
        type: Array,
        // required:true
    },

    created_on: {
        type: Date,

    },

    created_by: {
        type: Number,

    },
    updated_on: {
        type: Date,

    }
    ,
    updated_by: {
        type: Number,

    }
    ,  updated_reason: {
        type: String,
        default :'Direct Update'

    }
    ,
    deleted_on: {
        type: Date,

    }
    ,
    deleted_by: {
        type: Number,

    }, deleted_reason: {
        type: String,
        default :'Direct Delete'

    },
    tablestatus: {
        type: String,

    },
    image_path: {
        type: String,

    },
    serverpath: {
        type: String,

    },
});

const Agents = module.exports = mongoose.model('Agents', agentSchema);