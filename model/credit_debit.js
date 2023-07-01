var mongoose = require('mongoose');





const credit_debitSchema = mongoose.Schema({
    credit_debit_id_int: {
        type: Number,

    },
    customer_id_int: {
        type: Number,

    },
    account_id_int: {
        type: Number,

    },
    amount:{
        type: Number,

    },
    paid_or_received:{
        type: String,

    },
    paid_to_or_received_from:{
        type: String,

    },
    category_id_int:{
        type: Number,

    },
    payment_title:{
        type: String,

    },
    payment_mode:{
        type: String,

    },
    cheque_issued_to:{
        type: String,

    },
    cheque_date:{
        type: String,

    },
    cheque_no:{
        type: String,

    },cheque_bank:{
        type: String,

    }, transaction_ref:{ type: String,},transaction_id:{
        type: String,

    },otherdetails:{
        type: String,

    },
    payment_mode_name_if_other_selected:{
        type: String,

    },
    payment_date:{
        type: String,

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

const Credit_Debit = module.exports = mongoose.model('Credit_Debit', credit_debitSchema);