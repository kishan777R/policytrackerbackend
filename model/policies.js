var mongoose = require('mongoose');





const policySchema = mongoose.Schema({
    policy_id_int: {
        type: Number,

    },
    customer_id_int: {
        type: Number,

    },account_id_int: {
        type: Number,

    },
    user_id_int: {   // kiska policy khola h
        type: Number,

    },
    agent_id_int: {   // jis agent ne help ki h
        type: Number,

    },nomineeArr  : {
        type: Array, 
    }, 
     
    imageArr: {
        type: Array, 
    }, 
     
    tablestatus: {
        type: String,

    },  created_on: {
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

    },
    updated_reason: {
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

    },
    deleted_reason: {
        type: String,
        default :'Direct Delete'

    },log: {
        type: String,
        

    },
});

const Policy = module.exports = mongoose.model('Policy', policySchema);