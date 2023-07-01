var mongoose = require('mongoose'); 

const bankPostOfficeAccountSchema = mongoose.Schema({
    account_id_int: {
        type: Number, // it is as account_id_int and policy_id_int in task table

    },
    parent_account_id_int: {
        type: Number,

    },
    is_dependent:{   
        type: String,

    },
    customer_id_int :{   // for login orpose
        type: Number,

    },
    account_or_policy:{   
        type: String,

    },
    bank_or_post_office: {
        type: String,

    },
    account_opening_date: {
        type: String,

    },
    account_maturity_date: {
        type: String,

    },
    interest_rate_list: {
        type: Array,

    },
    mobile_given_for_account: {
        type: String,

    },
    email_given_for_account: {
        type: String,

    },
     address_given_for_account: {
        type: String,

    },

    organisation: {
        type: String,

    }, serverpath: {
        type: String,

    },
    organisation_img: {
        type: String,

    },
    user_id_int: {   // kiska account khola h
        type: Number,

    },
    agent_id_int: {   // jis agent ne help ki h
        type: Number,

    },
    ifsc: {
        type: String,

    },
    account_number: {
        type: String,

    },
    unique_id_for_account: {
        type: String,

    },
    account_title: {
        type: String,

    },
     
    is_adhaar_attached: {
        type: String,

    },
    is_netbanking_enabled: {
        type: String,

    },
    account_type: {
        type: String,

    },
    is_pancard_attached: {
        type: String,

    },
    postoffice_bank_address: {
        type: String,

    },
    nomineeArr  : {
        type: Array, 
    }, 
    hardCopyDocArr: {
        type: Array, 
    }, 
    imageArr: {
        type: Array, 
    }, 
    otherdetails: {
        type: String, 
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

    }, 
    deleted_reason: {
        type: String,
        default :'Direct Delete'

    },
    tablestatus: {
        type: String,

    },
    log: {
        type: String,
        

    },
});

const BankPostOfficeAccountSchema = module.exports = mongoose.model('bankPostOfficeAccountSchema', bankPostOfficeAccountSchema);