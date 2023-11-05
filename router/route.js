const express = require('express');
const http = require('http');
const https = require('https');
const qs = require('querystring');
const fs = require('fs');
const path = require('path');
require('dotenv').config();
const router = express.Router();
const Agents = require('../model/agents'); const Category = require('../model/category');

const Tasks = require('../model/tasks');
const BankPostOfficeAccounts = require('../model/bank_postoffice_accounts');
const Credit_Debit = require('../model/credit_debit');

var nodemailer = require('nodemailer');

var request = require("request"); 
const allconst = process.env ;
const adminemail = 'kishanrock777@gmail.com';

const myemail = "kishanrock777@gmail.com";
var transporter = nodemailer.createTransport({
  service: 'gmail',
  auth: {
    user: myemail,
    pass: 'harbarbhul@gmail.com'
  }
});
const multipart = require('connect-multiparty');

const multer = require("multer");
/// to do  this is diff in serve
const uploadAccountfiles = multer({ dest: "./public/uploads/account_doc" });
const uploadProfileImage = multer({ dest: "./public/uploads/profile_image" });
const uploadTaskfiles = multer({ dest: "./public/uploads/task_doc" });
const uploadAgentfiles = multer({ dest: "./public/uploads/agent_doc" });
const uploadTcredit_debitfiles = multer({ dest: "./public/uploads/credit_debit_doc" });

var created_by = 1;
const server_version_app = allconst.server_version_app;

const serverpath = allconst.serverpath;
console.log(serverpath);
mwUnique = {
  prevTimeId: 0,
  prevUniqueId: 0,
  getUniqueID: function () {
    try {
      var d = new Date();
      var newUniqueId = d.getTime();
      if (newUniqueId == mwUnique.prevTimeId)
        mwUnique.prevUniqueId = mwUnique.prevUniqueId + 1;
      else {
        mwUnique.prevTimeId = newUniqueId;
        mwUnique.prevUniqueId = 0;
      }
      newUniqueId = newUniqueId + '' + mwUnique.prevUniqueId;
      return newUniqueId;
    }
    catch (e) {
      console.log('mwUnique.getUniqueID error:' + e.message + '.');
    }
  }
};



const appname = "PolicyTracker";

const externalserverURL = "https://xcellinsindia.com";
const externalserverURLforcertificate = "http://lessons.co.in";

function onlyUnique(value, index, self) {
  return self.indexOf(value) === index;
}

function new_Date() {
  var d = new Date();
  // d.setHours(d.getHours() + 5);
  // d.setMinutes(d.getMinutes() + 30);
  return d;
}
function new_Date_time_not_required() {
  var d = new Date();

}



var formatDateComponent = function (dateComponent) {
  return (dateComponent < 10 ? '0' : '') + dateComponent;
};
function changedateformat(todayDate) {


  todayDate.setMinutes(todayDate.getMinutes() - todayDate.getTimezoneOffset());
  return todayDate.toISOString().slice(0, 10);
}
function changedateformat_for_string(todayDate) {


  todayDate.setMinutes(todayDate.getMinutes() - todayDate.getTimezoneOffset());
  var str = todayDate.toISOString().slice(0, 10);
  var arr = str.split('-');
  if (arr[1] == "01") {
    var month = "Jan";
  } else if (arr[1] == "02") {
    var month = "Feb";

  } else if (arr[1] == "03") {
    var month = "March";

  } else if (arr[1] == "04") {
    var month = "April";

  } else if (arr[1] == "05") {
    var month = "May";

  } else if (arr[1] == "06") {
    var month = "June";

  } else if (arr[1] == "07") {
    var month = "July";

  } else if (arr[1] == "08") {
    var month = "Aug";

  } else if (arr[1] == "09") {
    var month = "Sept";

  } else if (arr[1] == "10") {
    var month = "Oct";

  } else if (arr[1] == "11") {
    var month = "Nov";

  } else if (arr[1] == "12") {
    var month = "Dec";

  }
  return arr[2] + " " + month + " " + arr[0];
}
var formatDate = function (date) {
  return formatDateComponent(date.getMonth() + 1) + '/' + formatDateComponent(date.getDate()) + '/' + date.getFullYear();
};


router.post('/deleteFile', (req, res, next) => {
  console.log(req.body.filepath); console.log(req);
  fs.unlinkSync("public/" + req.body.filepath)

  res.json({ message: "File deleted succesfully !!", status: true, });


});

//Category start

router.delete('/category/:category_id_int/:logged_in_user_id_int/:customer_id_int', (req, res, next) => {
  Category.updateOne({ 'category_id_int': req.params.category_id_int }, { $set: { 'tablestatus': 'FALSE', 'deleted_by': req.params.logged_in_user_id_int, 'deleted_on': new Date() } }, async (err, deletedCategory) => {
    if (err) {
      res.json({ message: "Something is wrong", status: false });
    } else {


      Category.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.params.customer_id_int }] }, function (err, CategryList) {

        res.json({ message: "Category deleted Successfully !!", status: true, "CategryList": CategryList });
      }).sort({ category_id_int: -1 });


    }
  });
});

router.get('/category/:category_id_int', (req, res, next) => {
  Category.findOne({ $and: [{ "tablestatus": "TRUE" }, { "category_id_int": req.params.category_id_int }] }, function (err, CategoryOne) {
    res.json(CategoryOne);
  });
});
router.get('/categorylist/:customer_id_int', (req, res, next) => {
  Category.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.params.customer_id_int }] }, function (err, CategoryList) {
    res.json(CategoryList);
  }).sort({ category_id_int: -1 });
});


router.post('/category', (req, res, next) => {
  if (!req.body.category_id_int || req.body.category_id_int == 0) {

    // add start
    console.log("Adding category ");

    addCategory(req, res, next);
    // add end

  } else {
    // update start
    console.log("Updating category ");
    updateCategory(req, res, next);
    // update end
  }
});
addCategory = (req, res, next) => {
  Category.find({ $and: [{ "tablestatus": "TRUE" }, { category_title: req.body.category_title.trim() }, { "customer_id_int": req.params.customer_id_int }] }, function (err, CategryListC) {
    if (err) {
      res.json({ message: "Something is wrong " + err, status: false });
    } else {
      if (CategryListC.length == 0) {
        Category.findOne({}, function (err, lastCategory) {
          if (lastCategory) {
            var category_id_int = lastCategory.category_id_int + 1;
          } else {
            var category_id_int = 1;
          }
          let newCategoryOBJ = new Category({
            category_id_int: category_id_int,
            customer_id_int: req.body.customer_id_int,
            category_title: req.body.category_title.trim(),

            created_on: new Date(),
            created_by: req.body.logged_in_user_id_int,
            tablestatus: 'TRUE',
          });
          newCategoryOBJ.save((err, newCategory) => {
            if (err) {
              res.json({ message: "Something is wrong in adding Category" + err, status: false, newCategory: {} });
            } else {

              Category.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.params.customer_id_int }] }, function (err, CategryList) {

                res.json({ message: "Category Added Successfully !!", status: true, "CategryList": CategryList });
              }).sort({ category_id_int: -1 });

            }
          });
        }).sort({ category_id_int: -1 });
      } else {
        res.json({ message: "Category already exists  " + err, status: false });
      }
    }
  }).sort({ category_id_int: -1 });
}
updateCategory = (req, res, next) => {

  Category.find({ $and: [{ "tablestatus": "TRUE" }, { category_title: req.body.category_title.trim() }, { "customer_id_int": req.params.customer_id_int }] }, function (err, CategryListC) {
    if (err) {
      res.json({ message: "Something is wrong " + err, status: false });
    } else {
      if (CategryListC.length == 0 || (CategryListC.length == 1 && CategryListC[0].category_id_int == req.body.category_id_int)) {
        let updatedobj = {

          category_title: req.body.category_title.trim(),

          'updated_by': req.body.logged_in_user_id_int,
          'updated_on': new Date()
        };
        Category.updateOne({ 'category_id_int': req.body.category_id_int }, { $set: updatedobj }, (err, updatedObj) => {
          if (err) {
            res.json({ message: "Something is wrong " + err, status: false, updatedObj: {} });
          } else {
            Category.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.params.customer_id_int }] }, function (err, CategryList) {

              res.json({ message: "Category updated Successfully !!", status: true, "CategryList": CategryList });
            }).sort({ category_id_int: -1 });

          }
        });
      } else {
        res.json({ message: "Category already exists  " + err, status: false });
      }
    }
  }).sort({ category_id_int: -1 });
}
// Category end


//credit-debit start  
router.post("/uploadfileofcredit_debit", uploadTcredit_debitfiles.array("photos[]"), async (req, res) => {
  fileuploaCommon(req, res)
});

router.delete('/credit_debit/:credit_debit_id_int/:logged_in_user_id_int/:customer_id_int', (req, res, next) => {
  Credit_Debit.updateOne({ 'credit_debit_id_int': req.params.credit_debit_id_int }, { $set: { 'tablestatus': 'FALSE', 'deleted_by': req.params.logged_in_user_id_int, 'deleted_on': new Date() } }, async (err, deletedcredit_debit) => {
    if (err) {
      res.json({ message: "Something is wrong", status: false });
    } else {
      res.json({ message: "Record deleted Successfully !!", status: true });
    }
  });
});

router.get('/credit_debit/:credit_debit_id_int', (req, res, next) => {
  Credit_Debit.findOne({ $and: [{ "tablestatus": "TRUE" }, { "credit_debit_id_int": req.params.credit_debit_id_int }] }, function (err, credit_debitOne) {
    res.json(credit_debitOne);
  });
});
router.post('/credit_debitlist', async (req, res, next) => {

  let andArr = [{ "tablestatus": "TRUE" }, { "customer_id_int": req.body.customer_id_int }];
  //{name: {$in: ["Jonny", "Mia"]}}

  if (req.body.filterPaidrecieved[0]['selected'] && !req.body.filterPaidrecieved[1]['selected']) {
    andArr.push({ "paid_or_received": "Paid" })
  }
  if (!req.body.filterPaidrecieved[0]['selected'] && req.body.filterPaidrecieved[1]['selected']) {
    andArr.push({ "paid_or_received": "Received" })
  }

  let filterPayment_modeArr = [];

  req.body.filterPayment_mode.forEach((perPayMode) => {
    if (perPayMode.selected) {
      filterPayment_modeArr.push(perPayMode.value)
    }
  });
  if (filterPayment_modeArr.length > 0) {
    andArr.push({ payment_mode: { $in: filterPayment_modeArr } })
  }

  let filterAccountIdAccountingArr = [];

  req.body.filterAccountIdAccounting.forEach((perAcc) => {
    if (perAcc.selected) {
      filterAccountIdAccountingArr.push(perAcc.value)
    }
  });
  if (filterAccountIdAccountingArr.length > 0) {
    andArr.push({ account_id_int: { $in: filterPayment_modeArr } })
  }

  if (req.body.searchTerm.trim()) {
    andArr.push(
      {
        $or: [
          { amount: req.body.searchTerm.trim() },
          {  transaction_id: req.body.searchTerm.trim() }, 
          {  transaction_ref: req.body.searchTerm.trim() }, 
          {  payment_mode: req.body.searchTerm.trim() }, 
          {  payment_mode_name_if_other_selected: req.body.searchTerm.trim() }, 

          { paid_to_or_received_from: req.body.searchTerm.trim() },
          { payment_title: req.body.searchTerm.trim() },
          { otherdetails: req.body.searchTerm.trim() }
        ]
      }
    );
  }
  if (req.body.filterPayment_date1 && req.body.filterPayment_date2) {
  }
  let creditDebitListTotalRecord = 0;
  if (req.body.skip == 0) {
    creditDebitListTotalRecord = await Credit_Debit_total_ecod(req.body.customer_id_int);
  }

  console.log(andArr);
  Credit_Debit.find({ $and: andArr }, function (err, credit_debitList) {
    res.json({ "list": credit_debitList, "creditDebitListTotalRecord": creditDebitListTotalRecord });
  }).sort({ credit_debit_id_int: -1 }).limit(req.body.limit).skip(req.body.skip);
});
Credit_Debit_total_ecod = (customer_id_int) => {
  return Credit_Debit.countDocuments({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": customer_id_int }] }) ;
}

router.post('/credit_debit', (req, res, next) => {
  if (!req.body.credit_debit_id_int || req.body.credit_debit_id_int == 0) {

    // add start
    console.log("Adding credit_debit ");

    addcredit_debit(req, res, next);
    // add end

  } else {
    // update start
    console.log("Updating credit_debit ");
    updatecredit_debit(req, res, next);
    // update end
  }
});
addcredit_debit = (req, res, next) => {
  Credit_Debit.findOne({}, function (err, lastCredit_Debit) {
    if (lastCredit_Debit) {
      var credit_debit_id_int = lastCredit_Debit.credit_debit_id_int + 1;
    } else {
      var credit_debit_id_int = 1;
    }
    let newCredit_DebitOBJ = new Credit_Debit({
      credit_debit_id_int: credit_debit_id_int,
      customer_id_int: req.body.customer_id_int,
      account_id_int: req.body.account_id_int,
      amount: req.body.amount,
      paid_or_received: req.body.paid_or_received ? req.body.paid_or_received.trim() : '',
      category_id_int: req.body.category_id_int,
      payment_title: req.body.payment_title ? req.body.payment_title.trim() : '',
      payment_date: req.body.payment_date,
      paid_to_or_received_from: req.body.paid_to_or_received_from,


      payment_mode: req.body.payment_mode ? req.body.payment_mode.trim() : '',
      cheque_issued_to: req.body.cheque_issued_to ? req.body.cheque_issued_to.trim() : '',
      cheque_date: req.body.cheque_date ? req.body.cheque_date.trim() : '',
      cheque_no: req.body.cheque_no ? req.body.cheque_no.trim() : '',
      cheque_bank: req.body.cheque_bank ? req.body.cheque_bank.trim() : '',
      transaction_id: req.body.transaction_id ? req.body.transaction_id.trim() : '',
      otherdetails: req.body.otherdetails ? req.body.otherdetails.trim() : '',
      transaction_ref: req.body.transaction_ref ? req.body.transaction_ref.trim() : '',
      payment_mode_name_if_other_selected: req.body.payment_mode_name_if_other_selected ? req.body.payment_mode_name_if_other_selected.trim() : '',
      imageArr: req.body.imageArr,
      created_on: new Date(),
      created_by: req.body.logged_in_user_id_int,
      tablestatus: 'TRUE',
    });
    newCredit_DebitOBJ.save((err, newCredit_Debit) => {
      if (err) {
        res.json({ message: "Something is wrong in adding record" + err, status: false, newCredit_Debit: {} });
      } else {

        res.json({ message: "Record Added Successfully !!", status: true, });
      }
    });
  }).sort({ credit_debit_id_int: -1 });;
}
updatecredit_debit = (req, res, next) => {
  let updatedobj = {

    account_id_int: req.body.account_id_int,
    amount: req.body.amount,
    paid_or_received: req.body.paid_or_received ? req.body.paid_or_received.trim() : '',
    category_id_int: req.body.category_id_int,
    payment_title: req.body.payment_title ? req.body.payment_title.trim() : '',
    payment_date: req.body.payment_date,
    payment_mode: req.body.payment_mode ? req.body.payment_mode.trim() : '',
    cheque_issued_to: req.body.cheque_issued_to ? req.body.cheque_issued_to.trim() : '',
    cheque_date: req.body.cheque_date ? req.body.cheque_date.trim() : '',
    cheque_no: req.body.cheque_no ? req.body.cheque_no.trim() : '',
    cheque_bank: req.body.cheque_bank ? req.body.cheque_bank.trim() : '',
    transaction_id: req.body.transaction_id ? req.body.transaction_id.trim() : '',
    otherdetails: req.body.otherdetails ? req.body.otherdetails.trim() : '',
    paid_to_or_received_from: req.body.paid_to_or_received_from,
    transaction_ref: req.body.transaction_ref ? req.body.transaction_ref.trim() : '',
    payment_mode_name_if_other_selected: req.body.payment_mode_name_if_other_selected ? req.body.payment_mode_name_if_other_selected.trim() : '',


    imageArr: req.body.imageArr,
    'updated_by': req.body.logged_in_user_id_int,
    'updated_on': new Date()
  };
  Credit_Debit.updateOne({ 'credit_debit_id_int': req.body.credit_debit_id_int }, { $set: updatedobj }, (err, updatedObj) => {
    if (err) {
      res.json({ message: "Something is wrong " + err, status: false, updatedObj: {} });
    } else {
      res.json({ message: "Record updated Successfully !!", status: true,  });
    }
  });
}
// credit - debit end


//task start


router.post("/uploadfileoftask", uploadTaskfiles.array("photos[]"), async (req, res) => {
  fileuploaCommon(req, res)
});

router.delete('/task/:task_id_int/:logged_in_user_id_int/:customer_id_int', (req, res, next) => {
  Tasks.updateOne({ 'task_id_int': req.params.task_id_int }, { $set: { 'tablestatus': 'FALSE', 'deleted_by': req.params.logged_in_user_id_int, 'deleted_on': new Date() } }, async (err, deletedTask) => {
    if (err) {
      res.json({ message: "Something is wrong", status: false });
    } else {

      console.log(1);
      let taskData = await findOnetaskdata(req.params.task_id_int);
      console.log(3);
      if (taskData.task_for == 'Agent') {
        console.log(4);
        if (taskData.account_id_int) {

          let bankdatafroAcc = await findBankdata(taskData.account_id_int); console.log(7);
          if (bankdatafroAcc.hardCopyDocArr.length > 0) {
            let newhardCopyArr = [];
            bankdatafroAcc.hardCopyDocArr.forEach((perDoc) => {
              if (perDoc.task_id_int == req.params.task_id_int) {
                perDoc.task_id_int = perDoc.agent_id_int = '';

              }
              perDoc.da = new Date();
              newhardCopyArr.push({ ...perDoc });
            });
            await BankPostOfficeAccounts.updateOne({ 'account_id_int': taskData.account_id_int }, { $set: { 'hardCopyDocArr': newhardCopyArr, } }, async (err, obj) => {
              console.log(8); console.log(9);
            });
          }
        }
        if (taskData.policy_id_int) {
          console.log(10);
          let bankdatafropol = await findBankdata(taskData.policy_id_int);

          if (bankdatafropol.hardCopyDocArr.length > 0) {
            let newhardCopyArr2 = [];
            bankdatafropol.hardCopyDocArr.forEach((perDoc) => {
              if (perDoc.task_id_int == req.params.task_id_int) {
                perDoc.task_id_int = perDoc.agent_id_int = '';

              }
              perDoc.da = new Date();
              newhardCopyArr2.push({ ...perDoc });
            });
            await BankPostOfficeAccounts.updateOne({ 'account_id_int': taskData.policy_id_int }, { $set: { 'hardCopyDocArr': newhardCopyArr2, } }, async (err, obj) => {

            });
          }
        }
      }

      Tasks.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.params.customer_id_int }] }, function (err, TaskList) {
        console.log(13);
        res.json({ message: "Task deleted Successfully !!", status: true, "TaskList": TaskList });
      }).sort({ task_id_int: -1 });
    }
  });
});
function findOnetaskdata(task_id_int) {
  return Tasks.findOne({ $and: [{ "tablestatus": "TRUE" }, { "task_id_int": task_id_int }] }, async function (err, TaskOne) {

    console.log(2);

  });
}
function findBankdata(account_id_int) {


  return BankPostOfficeAccounts.findOne({ $and: [{ "tablestatus": "TRUE" }, { "account_id_int": account_id_int }] }, async function (err, AccOne) {

    console.log(6);

  });

}
router.get('/task/:task_id_int', (req, res, next) => {
  Tasks.findOne({ $and: [{ "tablestatus": "TRUE" }, { "task_id_int": req.params.task_id_int }] }, function (err, TaskOne) {
    res.json(TaskOne);
  });
});
router.get('/tasklist/:customer_id_int', (req, res, next) => {


  Tasks.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.params.customer_id_int }] }, function (err, TaskList) {
    res.json(TaskList);
  }).sort({ task_id_int: -1 });
});
router.post('/task', (req, res, next) => {
  if (!req.body.task_id_int || req.body.task_id_int == 0) {

    // add start
    console.log("Adding task");

    addTask(req, res, next);
    // add end

  } else {
    // update start
    console.log("Updating Task");
    updateTask(req, res, next);
    // update end
  }
});
addTask = (req, res, next) => {
  Tasks.findOne({}, function (err, lastTask) {
    if (lastTask) {
      var task_id_int = lastTask.task_id_int + 1;
    } else {
      var task_id_int = 1;
    }
    let newTaskOBJ = new Tasks({
      task_id_int: task_id_int,
      customer_id_int: req.body.customer_id_int,
      taskname: req.body.taskname ? req.body.taskname.trim() : '',
      taskpriority: req.body.taskpriority ? req.body.taskpriority.trim() : '',
      taskdetail: req.body.taskdetail ? req.body.taskdetail.trim() : '',
      account_id_int: req.body.account_id_int,
      policy_id_int: req.body.policy_id_int,


      tasklevel: req.body.tasklevel ? req.body.tasklevel.trim() : '',
      task_for: req.body.task_for ? req.body.task_for.trim() : '',
      task_for_id_int: req.body.task_for_id_int ? parseInt(req.body.task_for_id_int) : 0,
      adding_date: new Date(),
      start_date: req.body.start_date,
      expected_end_date: req.body.expected_end_date,
      start_time: req.body.start_time,
      expected_end_time: req.body.expected_end_time,

      imageArr: req.body.imageArr,
      created_on: new Date(),
      created_by: req.body.logged_in_user_id_int,
      tablestatus: 'TRUE',
    });
    newTaskOBJ.save((err, newTask) => {
      if (err) {
        res.json({ message: "Something is wrong in adding task" + err, status: false, newTask: {} });
      } else {

        if (req.body.policyHardCopyDocArr.length > 0) {
          let tmp = [];
          req.body.policyHardCopyDocArr.forEach((item) => {
            tmp.push({ ...item, task_id_int: newTask.task_id_int })
          });
          req.body.policyHardCopyDocArr = tmp;

        } if (req.body.accountHardCopyDocArr.length > 0) {

          let tmps = [];
          req.body.accountHardCopyDocArr.forEach((item) => {
            tmps.push({ ...item, task_id_int: newTask.task_id_int })
          });
          req.body.accountHardCopyDocArr = tmps;


        }

        if (req.body.policyHardCopyDocArr.length > 0 || req.body.accountHardCopyDocArr.length > 0) {
          if (req.body.policyHardCopyDocArr.length > 0) {

            updateHardocArryOfPolicy_alias_account('Added', "Policy", req.body.policy_id_int, req, res, next);
          } else {
            updateHardocArryOfPolicy_alias_account('Added', "Account", req.body.account_id_int, req, res, next);
          }
        } else {

          Tasks.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.body.customer_id_int }] }, function (err, TaskList) {
            res.json({ message: "Task Added Successfully !!", status: true, "TaskList": TaskList });
          }).sort({ task_id_int: -1 });
        }




      }
    });
  }).sort({ task_id_int: -1 });;
}
updateTask = (req, res, next) => {
  let updatedobj = {
    taskname: req.body.taskname ? req.body.taskname.trim() : '',
    taskdetail: req.body.taskdetail ? req.body.taskdetail.trim() : '',
    tasklevel: req.body.tasklevel ? req.body.tasklevel.trim() : '',
    taskpriority: req.body.taskpriority ? req.body.taskpriority.trim() : '',
    task_for: req.body.task_for ? req.body.task_for.trim() : '',
    task_for_id_int: req.body.task_for_id_int ? parseInt(req.body.task_for_id_int) : 0,
    adding_date: new Date(),
    start_date: req.body.start_date,
    expected_end_date: req.body.expected_end_date,
    start_time: req.body.start_time,
    expected_end_time: req.body.expected_end_time,
    inprogress_date: req.body.inprogress_date,
    complete_date: req.body.complete_date,
    inprogress_remark: req.body.inprogress_remark,
    complete_remark: req.body.complete_remark,
    account_id_int: req.body.account_id_int,
    policy_id_int: req.body.policy_id_int,
    competedRemarkIfPhsicalDoWereThere: req.body.competedRemarkIfPhsicalDoWereThere ? req.body.competedRemarkIfPhsicalDoWereThere : [],

    imageArr: req.body.imageArr,
    'updated_by': req.body.logged_in_user_id_int,
    'updated_on': new Date()
  };
  Tasks.updateOne({ 'task_id_int': req.body.task_id_int }, { $set: updatedobj }, (err, updatedObj) => {
    if (err) {
      res.json({ message: "Something is wrong " + err, status: false, updatedObj: {} });
    } else {

      if (req.body.policyHardCopyDocArr.length > 0 || req.body.accountHardCopyDocArr.length > 0) {
        if (req.body.policyHardCopyDocArr.length > 0) {

          updateHardocArryOfPolicy_alias_account("Updated", "Policy", req.body.policy_id_int, req, res, next);
        } else {
          updateHardocArryOfPolicy_alias_account("Updated", "Account", req.body.account_id_int, req, res, next);
        }
      } else {

        Tasks.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.body.customer_id_int }] }, function (err, TaskList) {
          res.json({ message: "Task updated Successfully !!", status: true, "TaskList": TaskList });
        }).sort({ task_id_int: -1 });
      }


    }
  });
}
updateHardocArryOfPolicy_alias_account = (action, what, col, req, res, next) => {
  let hardCopyDocArr = [];
  if (what == 'Policy') {
    hardCopyDocArr = req.body.policyHardCopyDocArr;
  } else {
    hardCopyDocArr = req.body.accountHardCopyDocArr;
  }
  let updatedobj = {
    'hardCopyDocArr': hardCopyDocArr,
    'updated_by': req.body.logged_in_user_id_int,
    'updated_on': new Date()
  };
  BankPostOfficeAccounts.updateOne({ 'account_id_int': col }, { $set: updatedobj }, (err, updatedObj) => {
    if (err) {
      res.json({ message: "Something is wrong " + err, status: false, updatedObj: {} });
    } else {
      if (what == 'Policy') {
        if (req.body.accountHardCopyDocArr.length > 0) {
          updateHardocArryOfPolicy_alias_account(action, "Account", req.body.account_id_int, req, res, next);
        } else {
          Tasks.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.body.customer_id_int }] }, function (err, TaskList) {
            res.json({ message: "Task " + action + " Successfully !!", status: true, "TaskList": TaskList });
          }).sort({ task_id_int: -1 });
        }
      } else {
        Tasks.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.body.customer_id_int }] }, function (err, TaskList) {
          res.json({ message: "Task " + action + " Successfully !!", status: true, "TaskList": TaskList });
        }).sort({ task_id_int: -1 });
      }
    }
  });
}


// task end
//agent start



router.post("/uploadfileAPI_agent_profile_image", uploadProfileImage.array("photos[]"), async (req, res) => {
  fileuploaCommon(req, res)


});


router.post("/uploadAgentfiles", uploadAgentfiles.array("photos[]"), async (req, res) => {
  fileuploaCommon(req, res)

});



router.delete('/agent/:agent_id_int/:extraaction/:UserOrAgent/:logged_in_user_id_int/:customer_id_int', (req, res, next) => {
  Agents.updateOne({ 'agent_id_int': req.params.agent_id_int }, { $set: { 'tablestatus': 'FALSE', 'deleted_by': req.params.logged_in_user_id_int, 'deleted_on': new Date() } }, async (err, deletedAgent) => {
    if (err) {
      res.json({ message: "Something is wrong", status: false });
    } else {

      if (req.params.UserOrAgent == 'User') {
        if (req.params.extraaction == 'assignAccountPoliciesToMe') {
          await assignAccountAndPolciesToMeBecauseOfUserDelete(req, res, req.params.agent_id_int, req.params.customer_id_int, req.params.logged_in_user_id_int);

        } else if (req.params.extraaction == 'deleteAccountPoliciesAsWell') {

          await deleteAccountAndPoliciesBecauseOfUserDelete(req, res, req.params.agent_id_int, req.params.logged_in_user_id_int);


        }
      } else if (req.params.UserOrAgent == 'Agent') {
        if (req.params.extraaction == 'assignTaskToMe') {
          await assignTaskToMeBecauseOfAgentDelete(req, res, req.params.agent_id_int, req.params.customer_id_int, req.params.logged_in_user_id_int);

        } else if (req.params.extraaction == 'deleteTaskAsWell') {
          await deleteTaskAsWellBecauseOfAgentDelete(req, res, req.params.agent_id_int, req.params.customer_id_int, req.params.logged_in_user_id_int);

        }
      }
    }
  });
});
assignTaskToMeBecauseOfAgentDelete = (req, res, agent_id_int, customer_id_int, logged_in_user_id_int) => {


  return Tasks.updateMany({ 'task_for_id_int': agent_id_int }, { $set: { 'task_for': 'My', 'task_for_id_int': 0, 'updated_reason': 'Agent was deleted, so account has to be assign to Admin automatically !', 'updated_by': logged_in_user_id_int, 'updated_on': new Date() } }, async (err, updatedAgent) => {
    if (err) {
      return { message: "User deleted Successfully but something is wrong in assigning this Agent's tasks to Admin", status: false };
    } else {
      let AgentList = await allagentList(req.params.customer_id_int);
      res.json({ message: "User deleted Successfully !!", status: true, "AgentList": AgentList });

    }
  });

}
deleteTaskAsWellBecauseOfAgentDelete = (req, res, agent_id_int, logged_in_user_id_int) => {


  return Tasks.updateMany({ 'task_for_id_int': agent_id_int }, { $set: { 'deleted_reason': 'Agent was deleted, so related tasks has to be deleted automatically !', 'tablestatus': 'FALSE', 'deleted_by': logged_in_user_id_int, 'deleted_on': new Date() } }, async (err, deletedTasks) => {
    if (err) {
      return { message: "User deleted Successfully but something is wrong in deleting tasks related to this Agent !", status: false };
    } else {
      let AgentList = await allagentList(req.params.customer_id_int);
      res.json({ message: "User deleted Successfully !!", status: true, "AgentList": AgentList });

    }
  });

}
assignAccountAndPolciesToMeBecauseOfUserDelete = (req, res, user_id_int, customer_id_int, logged_in_user_id_int) => {


  return BankPostOfficeAccounts.updateMany({ 'user_id_int': user_id_int }, { $set: { 'user_id_int': customer_id_int, 'updated_reason': 'Account holder was deleted, so account has to be assign to Admin automatically !', 'updated_by': logged_in_user_id_int, 'updated_on': new Date() } }, async (err, updatedAccount) => {
    if (err) {
      return { message: "User deleted Successfully but something is wrong in assigning this User's accounts and policies to you", status: false };
    } else {
      let AgentList = await allagentList(req.params.customer_id_int);
      res.json({ message: "User deleted Successfully !!", status: true, "AgentList": AgentList });

    }
  });

}

deleteAccountAndPoliciesBecauseOfUserDelete = (req, res, user_id_int, logged_in_user_id_int) => {


  return BankPostOfficeAccounts.updateMany({ 'user_id_int': user_id_int }, { $set: { 'deleted_reason': 'Account holder was deleted, so account has to be deleted automatically !', 'tablestatus': 'FALSE', 'deleted_by': logged_in_user_id_int, 'deleted_on': new Date() } }, async (err, deletedAccount) => {
    if (err) {
      return { message: "User deleted Successfully but something is wrong in deleting account and policies related to this User !", status: false };
    } else {


      let AgentList = await allagentList(req.params.customer_id_int);
      res.json({ message: "User deleted Successfully !!", status: true, "AgentList": AgentList });



    }
  });

}

allagentList = (customer_id_int) => {
  return Agents.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": customer_id_int }] }, function (err, AgentList) {
    return AgentList;
  }).sort({ agent_id_int: -1 });
}
router.get('/agent/:agent_id_int', (req, res, next) => {
  Agents.findOne({ $and: [{ "tablestatus": "TRUE" }, { "agent_id_int": req.params.agent_id_int }] }, function (err, AgentOne) {
    res.json(AgentOne);
  });
});
router.get('/agentlist/:customer_id_int', async (req, res, next) => {
  let AgentList = await allagentList(req.params.customer_id_int);

  res.json(AgentList);

});
router.post('/agent', (req, res, next) => {
  if (!req.body.agent_id_int || req.body.agent_id_int == 0) {

    // add start
    console.log("Adding  ");

    addAgent(req, res, next);
    // add end

  } else {
    // update start
    console.log("Updating  ");
    updateAgent(req, res, next);
    // update end
  }
});
addAgent = (req, res, next) => {
  Agents.find({ $and: [{ "mobile": req.body.mobile.trim() }, { "customer_id_int": req.body.customer_id_int }, { "tablestatus": "TRUE" }] }, function (err, Agentlist) {
    if (Agentlist.length > 0) {
      res.json({ message: "Data with this mobile is already added !!", status: false, newAgent: {} });

    } else {
      Agents.findOne({}, function (err, lastAgent) {
        if (lastAgent) {
          var agent_id_int = lastAgent.agent_id_int + 1;
        } else {
          var agent_id_int = 1;
        }
        let newAgentOBJ = new Agents({
          agent_id_int: agent_id_int,
          agent_added_by_id_int: req.body.logged_in_user_id_int,
          customer_id_int: req.body.customer_id_int,


          name: req.body.name ? req.body.name.trim() : '',
          mobile: req.body.mobile ? req.body.mobile.trim() : '',
          email: req.body.email ? req.body.email.trim() : '',
          alt_mobile: req.body.alt_mobile ? req.body.alt_mobile.trim() : '',
          state: req.body.state ? req.body.state.trim() : '',
          city: req.body.city ? req.body.city.trim() : '',
          address: req.body.address ? req.body.address.trim() : '',
          pincode: req.body.pincode ? req.body.pincode : '',
          serverpath: req.body.serverpath ? req.body.serverpath.trim() : '',
          image_path: req.body.image_path ? req.body.image_path.trim() : '',
          working_for_user_or_agent: req.body.working_for_user_or_agent ? req.body.working_for_user_or_agent : 'Agent',
          dob: req.body.dob ? req.body.dob.trim() : '',
          aadhaar: req.body.aadhaar ? req.body.aadhaar.trim() : '',
          pancard: req.body.pancard ? req.body.pancard.trim() : '',

          imageArr: req.body.imageArr,
          created_on: new Date(),
          created_by: req.body.logged_in_user_id_int,
          tablestatus: 'TRUE',
          licenseArr: req.body.licenseArr
        });


        newAgentOBJ.save((err, newAgent) => {
          if (err) {
            res.json({ message: "Something is wrong in adding agent" + err, status: false, newAgent: {} });
          } else {
            Agents.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.body.customer_id_int }] }, function (err, AgentList) {
              res.json({ message: "  Added Successfully !!", status: true, "AgentList": AgentList });
            }).sort({ agent_id_int: -1 });



          }
        });

      }).sort({ agent_id_int: -1 });;

    }
  });
}
updateAgent = (req, res, next) => {
  Agents.find({ $and: [{ "mobile": req.body.mobile.trim() }, { "agent_id_int": { $ne: req.body.agent_id_int } }, { "customer_id_int": req.body.customer_id_int }, { "tablestatus": "TRUE" }] }, function (err, Agentlist) {
    if (Agentlist && Agentlist.length > 0) {
      res.json({ message: "Data with this mobile already exists !!", status: false, newAgent: {} });
    } else {

      let updatedobj = {
        name: req.body.name ? req.body.name.trim() : '',
        mobile: req.body.mobile ? req.body.mobile.trim() : '',
        email: req.body.email ? req.body.email.trim() : '',
        alt_mobile: req.body.alt_mobile ? req.body.alt_mobile.trim() : '',
        state: req.body.state ? req.body.state.trim() : '',
        city: req.body.city ? req.body.city.trim() : '',
        address: req.body.address ? req.body.address.trim() : '',
        pincode: req.body.pincode ? req.body.pincode : '',
        working_for_user_or_agent: req.body.working_for_user_or_agent ? req.body.working_for_user_or_agent : 'Agent',
        serverpath: req.body.serverpath ? req.body.serverpath.trim() : '',
        image_path: req.body.image_path ? req.body.image_path.trim() : '',
        'updated_by': req.body.logged_in_user_id_int,
        'updated_on': new Date(),
        licenseArr: req.body.licenseArr,
        dob: req.body.dob ? req.body.dob.trim() : '',
        aadhaar: req.body.aadhaar ? req.body.aadhaar.trim() : '',
        pancard: req.body.pancard ? req.body.pancard.trim() : '',

        imageArr: req.body.imageArr,
      };
      Agents.updateOne({ 'agent_id_int': req.body.agent_id_int }, { $set: updatedobj }, (err, updatedObj) => {
        if (err) {
          res.json({ message: "Something is wrong " + err, status: false, updatedObj: {} });
        } else {
          Agents.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.body.customer_id_int }] }, function (err, AgentList) {
            res.json({ message: "Updated successfully !!", status: true, "AgentList": AgentList });
          }).sort({ agent_id_int: -1 });
        }
      });
    }
  });
}

//agent end


//account start 


router.post("/uploadAccountfiles", uploadAccountfiles.array("photos[]"), async (req, res) => {
  fileuploaCommon(req, res)

});



router.delete('/account/:account_id_int/:extraaction/:logged_in_user_id_int/:customer_id_int', (req, res, next) => {
  BankPostOfficeAccounts.updateOne({ 'account_id_int': req.params.account_id_int }, { $set: { 'tablestatus': 'FALSE', 'deleted_by': req.params.logged_in_user_id_int, 'deleted_on': new Date() } }, async (err, deletedAccount) => {
    if (err) {
      res.json({ message: "Something is wrong", status: false });
    } else {

      if (req.params.extraaction == 'justdeleteaccount') {
        await emptyPolicyIdAndAccountIdFromTasksListBecauseAccountDeleted(req, res);

      } else if (req.params.extraaction == 'deletePoliciesTasksForAccount') {

        await deleteTasksBecauseOfAccountDelete(req, res);


      }

    }
  });
});
deleteTasksBecauseOfAccountDelete = (req, res) => {


  return Tasks.updateMany({ 'account_id_int': req.params.account_id_int }, { $set: { 'tablestatus': 'FALSE', 'deleted_by': req.params.logged_in_user_id_int, 'deleted_on': new Date() } }, async (err, deletedtasks) => {
    if (err) {
      return { message: "Account deleted Successfully but something is wrong in deleting its related tasks !", status: false };
    } else {
      await deletePoliciesBecauseOfAccountDelete(req, res);

    }
  });

}
deletePoliciesBecauseOfAccountDelete = (req, res) => {


  return BankPostOfficeAccounts.updateMany({ 'parent_account_id_int': req.params.account_id_int }, { $set: { 'tablestatus': 'FALSE', 'deleted_by': req.params.logged_in_user_id_int, 'deleted_on': new Date() } }, async (err, deletedtasks) => {
    if (err) {
      return { message: "Account deleted Successfully but something is wrong in deleting its related policies !", status: false };
    } else {

      BankPostOfficeAccounts.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.params.customer_id_int }] }, function (err, AccountList) {
        res.json({ message: "Deleted Successfully !!", status: true, "AccountList": AccountList });
      }).sort({ account_id_int: -1 });

    }
  });

}
emptyPolicyIdAndAccountIdFromTasksListBecauseAccountDeleted = (req, res) => {


  return Tasks.updateMany({ 'account_id_int': req.params.account_id_int }, { $set: { 'account_id_int': '', 'policy_id_int': '', 'updated_by': req.params.logged_in_user_id_int, 'updated_on': new Date() } }, async (err, deletedtasks) => {
    if (err) {
      return { message: "Account deleted Successfully but something is wrong in detaching account from its related tasks !", status: false };
    } else {


      await emptyAccountIdFromPolicyListBecauseAccountDeleted(req, res);



    }
  });

}
emptyAccountIdFromPolicyListBecauseAccountDeleted = (req, res) => {


  return BankPostOfficeAccounts.updateMany({ 'parent_account_id_int': req.params.account_id_int }, { $set: { 'parent_account_id_int': '', 'updated_by': req.params.logged_in_user_id_int, 'updated_on': new Date() } }, async (err, deletedtasks) => {
    if (err) {
      return { message: "Account deleted Successfully but something is wrong in detaching account from its related polcies !", status: false };
    } else {

      BankPostOfficeAccounts.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.params.customer_id_int }] }, function (err, AccountList) {
        res.json({ message: "Deleted Successfully !!", status: true, "AccountList": AccountList });
      }).sort({ account_id_int: -1 });
    }
  });

}

router.get('/account/:account_id_int', (req, res, next) => {
  BankPostOfficeAccounts.findOne({ $and: [{ "tablestatus": "TRUE" }, { "account_id_int": req.params.account_id_int }] }, function (err, AccountOne) {
    res.json(AccountOne);
  });
});
router.get('/accountlist/:customer_id_int', (req, res, next) => {
  BankPostOfficeAccounts.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.params.customer_id_int }] }, function (err, AccountList) {
    res.json(AccountList);
  }).sort({ account_id_int: -1 });
});
router.post('/account', (req, res, next) => {
  if (!req.body.account_id_int || req.body.account_id_int == 0) {

    // add start
    console.log("Adding account ");

    addAccount(req, res, next);
    // add end

  } else {
    // update start
    console.log("Updating  account");
    updateAccount(req, res, next);
    // update end
  }
});
addAccount = (req, res, next) => {
  BankPostOfficeAccounts.find({ $and: [{ "unique_id_for_account": req.body.unique_id_for_account.trim() }, { "customer_id_int": req.body.customer_id_int }, { "tablestatus": "TRUE" }] }, function (err, AccountList) {
    if (AccountList.length > 0) {
      res.json({ message: "Data with this Customer Id/CIF Id  is already added !!", status: false, newAccount: {} });

    } else {
      BankPostOfficeAccounts.findOne({}, function (err, lastAccount) {
        if (lastAccount) {
          var account_id_int = lastAccount.account_id_int + 1;
        } else {
          var account_id_int = 1;
        }

        let newAccountOBJ = new BankPostOfficeAccounts({
          account_id_int: account_id_int,
          account_title: req.body.account_title,
          customer_id_int: req.body.customer_id_int,
          bank_or_post_office: req.body.bank_or_post_office,
          account_or_policy: req.body.account_or_policy,
          organisation: req.body.organisation,
          organisation_img: req.body.organisation.replace('', '_') + '.JPEG',
          account_number: req.body.account_number,
          agent_id_int: req.body.agent_id_int,
          user_id_int: req.body.user_id_int,
          account_opening_date: req.body.account_opening_date,
          account_maturity_date: req.body.account_maturity_date,
          is_dependent: req.body.is_dependent,

          ifsc: req.body.ifsc,
          unique_id_for_account: req.body.unique_id_for_account,
          otherdetails: req.body.otherdetails,
          parent_account_id_int: req.body.parent_account_id_int,
          is_netbanking_enabled: req.body.is_netbanking_enabled,

          is_adhaar_attached: req.body.is_adhaar_attached,
          account_type: req.body.account_type,
          is_pancard_attached: req.body.is_pancard_attached,
          hardCopyDocArr: req.body.hardCopyDocArr,
          mobile_given_for_account: req.body.mobile_given_for_account,
          email_given_for_account: req.body.email_given_for_account,

          address_given_for_account: req.body.address_given_for_account,
          interest_rate_list: req.body.interest_rate_list,

          postoffice_bank_address: req.body.postoffice_bank_address,
          nomineeArr: req.body.nomineeArr,
          serverpath: serverpath,
          imageArr: req.body.imageArr,
          created_on: new Date(),
          created_by: req.body.logged_in_user_id_int,
          tablestatus: 'TRUE',
        });


        newAccountOBJ.save((err, newAccount) => {
          if (err) {
            res.json({ message: "Something is wrong in adding account" + err, status: false, newAccount: {} });
          } else {
            BankPostOfficeAccounts.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.body.customer_id_int }] }, function (err, AccountList) {
              res.json({ message: "  Added Successfully !!", status: true, "AccountList": AccountList });
            }).sort({ account_id_int: -1 });



          }
        });

      }).sort({ account_id_int: -1 });;

    }
  });
}
updateAccount = (req, res, next) => {
  BankPostOfficeAccounts.find({ $and: [{ "unique_id_for_account": req.body.unique_id_for_account.trim() }, { "account_id_int": { $ne: req.body.account_id_int } }, { "customer_id_int": req.body.customer_id_int }, { "tablestatus": "TRUE" }] }, function (err, AccountList) {
    if (AccountList && AccountList.length > 0) {
      res.json({ message: "Data with this Customer Id/CIF Id already exists !!", status: false, newAgent: {} });
    } else {

      let updatedobj = {
        parent_account_id_int: req.body.parent_account_id_int,
        is_dependent: req.body.is_dependent,
        user_id_int: req.body.user_id_int,
        bank_or_post_office: req.body.bank_or_post_office,
        ifsc: req.body.ifsc, interest_rate_list: req.body.interest_rate_list,
        otherdetails: req.body.otherdetails,
        account_or_policy: req.body.account_or_policy,
        account_number: req.body.account_number,
        unique_id_for_account: req.body.unique_id_for_account,
        is_netbanking_enabled: req.body.is_netbanking_enabled,
        account_title: req.body.account_title,
        is_adhaar_attached: req.body.is_adhaar_attached,
        account_type: req.body.account_type,
        is_pancard_attached: req.body.is_pancard_attached,
        postoffice_bank_address: req.body.postoffice_bank_address,
        imageArr: req.body.imageArr, hardCopyDocArr: req.body.hardCopyDocArr,
        agent_id_int: req.body.agent_id_int,
        organisation_img: req.body.organisation.replace('', '_') + '.JPEG',
        account_opening_date: req.body.account_opening_date,
        account_maturity_date: req.body.account_maturity_date,
        organisation: req.body.organisation,
        mobile_given_for_account: req.body.mobile_given_for_account,
        email_given_for_account: req.body.email_given_for_account,
        serverpath: serverpath,
        address_given_for_account: req.body.address_given_for_account,
        nomineeArr: req.body.nomineeArr,
        'updated_by': req.body.logged_in_user_id_int,
        'updated_on': new Date(),
      };
      BankPostOfficeAccounts.updateOne({ 'account_id_int': req.body.account_id_int }, { $set: updatedobj }, (err, updatedObj) => {
        if (err) {
          res.json({ message: "Something is wrong " + err, status: false, updatedObj: {} });
        } else {
          BankPostOfficeAccounts.find({ $and: [{ "tablestatus": "TRUE" }, { "customer_id_int": req.body.customer_id_int }] }, function (err, AccountList) {
            res.json({ message: "Updated successfully !!", status: true, "AccountList": AccountList });
          }).sort({ account_id_int: -1 });
        }
      });
    }
  });
}

//account end





/// initial data start
router.get('/initialData', (req, res, next) => {
  let initialData = {
    'bankList': {
      "special_banks": [

        { "name": "HDFC", "logo": "" },
      ], "banks": [

        { "name": "HDFC", "logo": "" },
      ]
    },
    'stateList': ['Delhi', 'UP'],

    'organisation_typeList': [{ 'name': 'LIC' }, { 'name': 'Bank' }, { 'name': 'Post Office' }],
    'polcyTileList': [{
      rowKey: [
        {
          'link': '',
          'title': 'Nominees', 'img': 'assets/bank-users-icon.jpg',
          'imgstyle': "width: 68%;",
          'subtitle': 'List of nominees for this policy',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black",
          'functionName': "",
          'id': "open-modal"
        },
        {
          'link': '',
          'title': 'Documents ', 'img': 'assets/doc-icon.jpg', 'imgstyle': "  width: 68%;",
          'subtitle': 'Docs related to this policy',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black",
          'functionName': "",
          'id': "open-modalDocs"

        },
      ]
    }],
    'accountTileList': [{
      rowKey: [
        {
          'link': '',
          'title': 'Nominees', 'img': 'assets/bank-users-icon.jpg',
          'imgstyle': "width: 68%;",
          'subtitle': 'List of nominees for this account',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black",
          'functionName': "",
          'id': "open-modal"
        },
        {
          'link': '',
          'title': 'Policies', 'img': 'assets/bank-policies-icon.jpg',
          'imgstyle': " width: 70%;",
          'subtitle': 'Policies under this account',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black",
          'functionName': "redirectToPoliciesUnderThisAccount",
          'id': "Pol"
        }
      ]
    }, {
      rowKey: [

        {
          'link': '',
          'title': 'Documents ', 'img': 'assets/doc-icon.jpg', 'imgstyle': "  width: 68%;",
          'subtitle': 'Docs related to this account',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black",
          'functionName': "",
          'id': "open-modalDocs"

        },
        {


        }
      ]
    }],

    'balanceRecordTileList': [ {
      rowKey: [

        {
          'link': '',
          'title': 'Documents ', 'img': 'assets/doc-icon.jpg', 'imgstyle': "  width: 68%;",'titlestyle': "  margin: 15px;",
          'subtitle': 'Docs related to record',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black",
          'functionName': "",
          'id': "open-modalDocs"

        },
        {


        }
      ]
    }],

    'userTileList': [{
      rowKey: [
        {
          'link': '',
          'title': 'Accounts', 'img': 'assets/bank-account-icon.jpg',
          'imgstyle': "   width: 60%;",
          'subtitle': 'Accounts of this user',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black",
          'functionName': "redirectUserAgentToAccount",
          'id': "Acc"
        },
        {
          'link': '',
          'title': 'Policies', 'img': 'assets/bank-policies-icon.jpg',
          'imgstyle': " width: 76%;",
          'subtitle': 'Policies for this user',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black",
          'functionName': "redirectUserAgentToPolicies",
          'id': "Pol"
        }
      ]
    }, {
      rowKey: [

        {
          'link': '',
          'title': 'Documents ', 'img': 'assets/doc-icon.jpg', 'imgstyle': "  width: 68%;",
          'subtitle': 'Docs related to user',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black",
          'functionName': "",
          'id': "open-modalDocs"

        },
        {


        }
      ]
    }],
    'agentTileList': [
      {
        rowKey: [
          {
            'link': '',
            'title': 'Accounts', 'img': 'assets/bank-account-icon.jpg',
            'imgstyle': "  width: 60%;",

            'subtitle': 'Accounts by this agent',
            'cardColor': '#e3edff',
            'titleColor': "black",
            'subtitleColor': "black",
            'functionName': "redirectUserAgentToAccount",
            'id': "Acc"
          },
          {
            'link': '',
            'title': 'Policies', 'img': 'assets/bank-policies-icon.jpg',
            'imgstyle': " width: 76%;",
            'subtitle': 'Policies by this agent',
            'cardColor': '#e3edff',
            'titleColor': "black",
            'subtitleColor': "black",
            'functionName': "redirectUserAgentToPolicies",
            'id': "Pol"
          },

        ]
      },
      {
        rowKey: [

          {
            'link': '',
            'title': 'Agent Tasks ', 'img': 'assets/task-icon.jpg', 'imgstyle': "height: 55%; width: 71%;  margin-left: 11px;",
            'subtitle': 'Create and track tasks for agent',
            'cardColor': '#e3edff',
            'titleColor': "black",
            'subtitleColor': "black",
            'functionName': "redirectToHistasks",
            'id': "Task"
          },
          {
            'link': '',
            'title': 'Documents ', 'img': 'assets/doc-icon.jpg', 'imgstyle': "  width: 68%;",
            'subtitle': 'Docs related to agent',
            'cardColor': '#e3edff',
            'titleColor': "black",
            'subtitleColor': "black",
            'functionName': "",
            'id': "open-modalDocs"

          }
        ]
      }],
    'hometilesListBelowcard': [{
      rowKey: [{
        'link': '../accounting',
        'title': 'Accounting', 'img': 'assets/accounting.jpg',
        'imgstyle': "height: 90%;  width: 75%;",
        'subtitle': 'Manage Payments (Paid, Received)',
        'cardColor': '#e3edff',
        'titleColor': "black",
        'subtitleColor': "black"

      },
      {
        'link': '../agents/Agent',
        'title': 'Quick Notes', 'img': 'assets/notes.jpg',
        'imgstyle': "height: 90%;  width: 75%;",
        'subtitle': 'Take note of something urgent',
        'cardColor': '#e3edff',
        'titleColor': "black",
        'subtitleColor': "black"

      }
        ,
      {
        'link': '../accounts',
        'title': 'Report', 'img': 'assets/balancereport.jpg',
        'imgstyle': " width: 93%;",
        'subtitle': 'Check Account and Poliicy Balance',
        'cardColor': '#e3edff',
        'titleColor': "black",
        'subtitleColor': "black"

      },
      ],

    }],
    'tilesList': [
      {
        rowKey: [
          
          {
            'link': '/agent/User',
            'title': 'Users', 'img': 'assets/bank-users-icon.jpg', 'imgstyle': "  width: 96%;",
            'subtitle': 'Policy Holders, Nominees etc',
            'cardColor': '#e3edff',
            'titleColor': "black",
            'subtitleColor': "black"

          },
          {
            'link': '/agent/Agent',
            'title': 'Agents', 'img': 'assets/bank-agent-icon.jpg',
            'imgstyle': "height: 90%;  width: 75%;",
            'subtitle': 'Helpers of your Bank/P.O tasks',
            'cardColor': '#e3edff',
            'titleColor': "black",
            'subtitleColor': "black"
  
          },
          {
          'link': '/accounts/Account/ALL/-1',
          'title': 'Accounts', 'img': 'assets/bank-account-icon.jpg',
          'imgstyle': "height: 90%;  width: 75%;",
          'subtitle': 'Bank or Post Office Accounts',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black"

        },
       
           
       
        ]
      }
      ,

      {
        rowKey: [
           {
          'link': '/accounts/Policy/ALL/-1',
          'title': 'Policies', 'img': 'assets/bank-policies-icon.jpg',
          'imgstyle': " width: 93%;",
          'subtitle': 'FD, KVP, Mutual Fund, LIC etc',
          'cardColor': '#e3edff',
          'titleColor': "black",
          'subtitleColor': "black"

        },
          {
            'link': '/tasks',
            'title': 'Tasks', 'img': 'assets/task-icon.jpg', 'imgstyle': "height: 90%;  width: 75%;",
            'subtitle': 'Create and track your to do tasks',
            'cardColor': '#e3edff',
            'titleColor': "black",
            'subtitleColor': "black"

          },
          {
            'link': '../agents',
            'title': 'Documents', 'img': 'assets/doc-icon.jpg', 'imgstyle': "  width: 91%;",
            'subtitle': 'All your important docs',
            'cardColor': '#e3edff',
            'titleColor': "black",
            'subtitleColor': "black"

          }
        ]
      }
    ],
    dashboardHeader: [

      {
        'title': 'Accounts', 'titlestyle': "  color: black;",
        'subtitle': '12', 'subtitlestyle': "font-size:20px;  color: red;",

      },
      {
        'title': 'Balance(INR)', 'titlestyle': "  color: black;",
        'subtitle': '1232343', 'subtitlestyle': " font-size:20px; color: green;",

      }, {
        'title': 'Policies', 'titlestyle': "  color: black;",
        'subtitle': '212', 'subtitlestyle': " font-size:20px; color: blue;",

      },
    ]
  }
  res.json({ initialData: initialData });

});
///  initial data end


///fil upload coommon
fileuploaCommon = (req, res, next) => {
  let filePathArr = [];
  let imagePathArr = [];
  req.files.forEach((perFile) => {
    var image_path = perFile.path.replace("\\", "/");
    image_path = image_path.replace("\\", "/");
    image_path = image_path.replace("public/", "");
    fs.renameSync("public/" + image_path, "public/" + image_path + '_' + perFile.originalname);
    filePathArr.push(serverpath + '' + image_path + '_' + perFile.originalname);
    let filetype = 'Document';
    if (perFile.mimetype.includes('image')) {
      filetype = 'Image';
    }
    imagePathArr.push({ "serverpath": serverpath, "originalname": perFile.originalname, "filepath": image_path + '_' + perFile.originalname, "filetype": filetype });
  });
  res.json({ message: "File uploaded succesfully !!", status: true, filePathArr: filePathArr, serverpath: serverpath, imagePathArr: imagePathArr });


}
///file upload common end
module.exports = router;