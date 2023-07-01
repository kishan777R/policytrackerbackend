var mongoose = require('mongoose'); 

const categorySchema = mongoose.Schema({
   
    category_id_int: {
        type: Number,

    },
    customer_id_int: {
        type: Number,

    },
     
    category_title: {
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
    tablestatus: {
        type: String,

    },
     
});

const Category = module.exports = mongoose.model('Category', categorySchema);