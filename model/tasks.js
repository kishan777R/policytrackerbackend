var mongoose = require('mongoose');

const taskSchema = mongoose.Schema({
    task_id_int: {
        type: Number,
    },
    customer_id_int: {
        type: Number,
    },
    
    task_for_id_int: {
        type: Number,

    },
    account_id_int: {
        type: Number,

    },
      
    policy_id_int: {
        type: Number,

    },
    task_for: {
        type: String, 
    },
    
    taskname: {
        type: String, 
    },
    taskdetail: {
        type: String, 
    },
     taskpriority: {
        type: String, 
    },
    tasklevel: {
        type: String, 
    }, 
    adding_date: {
        type: Date,
    },
    start_date:{
        type: String,
    },
    start_time:{
        type : String
    },

    expected_end_date: {
        type: String,
    },
    expected_end_time: {
        type: String,
    },
    inprogress_date: {
        type: String,
    },
    complete_date: {
        type: String,
    },
    inprogress_remark: {
        type: String,
    },
    complete_remark: {
        type: String,
    }, 
    imageArr: {
        type: Array, 
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

    }, log: {
        type: String,
        

    },

});

const Tasks = module.exports = mongoose.model('Tasks', taskSchema);