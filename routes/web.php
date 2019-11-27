<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::prefix('/')->group(function () {
    if (isMobile()){
        Route::get('/',"Mobile\IndexController@index");
    }else{
        Route::get('/',"Home\IndexController@index");
    }
});
/**
 * 手机模块
 */
Route::prefix("mobile")->middleware(['web'])->group(function (){
    Route::get('/',"Mobile\IndexController@index");
    Route::prefix("/page")->middleware(['login'])->group(function (){
        Route::get('/',"Home\PageController@index");
        Route::get('/webinfo',"Mobile\PageController@webInfo");
        Route::get('/clearcache',"Mobile\PageController@clearCache");
        Route::prefix("/user")->group(function (){
            Route::get('/adduser',"Mobile\UserController@addIndex");
            Route::get('/userlist',"Mobile\UserController@userList");
            Route::get('/adminlist',"Mobile\UserController@adminList");
            Route::post('/adduser',"Mobile\UserController@addUser");
            Route::get('/addadmin',"Mobile\UserController@addAdminIndex");
            Route::post('/addadmin',"Mobile\UserController@addAdmin");
            Route::get('/dumpuser',"Mobile\UserController@dumpUser");
            Route::get('/dumpadmin',"Mobile\UserController@dumpAdmin");
            Route::post('/dumpuser',"Mobile\UserController@dumpUserList");
            Route::post('/dumpadmin',"Mobile\UserController@dumpAdminList");
            Route::post('/search',"Mobile\UserController@search");
            Route::get('/search/username',"Mobile\UserController@getUserListByUsername");
            Route::get('/search/addr',"Mobile\UserController@getUserListByAddr");
            Route::get('/search/age',"Mobile\UserController@getUserListByAge");
            Route::get('/search/tel',"Mobile\UserController@getUserListByTel");
            Route::get('/search/time',"Mobile\UserController@getUserListByTime");
        });
    });
    Route::prefix("/login")->group(function (){
        Route::get("/","Mobile\LoginController@index");
        Route::post("/","Mobile\LoginController@doLogin");
        Route::post("/logout","Mobile\LoginController@logout");
    });
});
/**
 * home模块
 */
Route::prefix("home")->middleware(['mobile'])->group(function (){
    Route::get('/',"Home\IndexController@index");
    Route::get('/captcha/{id}',"Home\PageController@captcha");
    Route::prefix("/page")->middleware(['login'])->group(function (){
        Route::get('/',"Home\PageController@index");
        Route::get('/webinfo',"Home\PageController@webInfo");
        Route::get('/clearcache',"Home\PageController@clearCache");
        Route::get("/tips","Home\PageController@tips");
        Route::prefix("/class")->group(function (){
            Route::get('/list',"Home\PageController@classList");
            Route::get('/add',"Home\PageController@classAdd");
            Route::get('/edit/{id}',"Home\PageController@classEdit");
            Route::post('/add',"Home\PageController@classAddResult");
            Route::post('/edit',"Home\PageController@classEditResult");
            Route::get('/del/{id}/{token}',"Home\PageController@classDel");
            Route::get('/merge',"Home\PageController@classMerge");
            Route::post('/merge',"Home\PageController@classMergeResult");
            Route::get('/distribution','Home\PageController@classDistribution');
            Route::post('/distribution','Home\PageController@classDistributionResults');
        });
        Route::prefix("/department")->group(function (){
            Route::get('/',"Home\PageController@department");
            Route::get('/add',"Home\PageController@addDepartment");
            Route::post('/add',"Home\PageController@addDepartmentResult");
            Route::get('/edit/{id}',"Home\PageController@editDepartment");
            Route::post('/edit',"Home\PageController@editDepartmentResult");
            Route::get('/del/{id}/{token}',"Home\PageController@delDepartment");
            Route::get('/merge',"Home\PageController@mergeDepartment");
            Route::post('/merge',"Home\PageController@mergeDepartmentResult");
        });
        Route::prefix("/channel")->group(function (){
            Route::get('/',"Home\PageController@channel");
            Route::get('/add',"Home\PageController@addChannel");
            Route::post('/add',"Home\PageController@addChannelResult");
            Route::get('/edit/{id}',"Home\PageController@editChannel");
            Route::post('/edit',"Home\PageController@editChannelResult");
            Route::get('/del/{id}/{token}',"Home\PageController@delChannel");
            Route::get('/merge',"Home\PageController@mergeChannel");
            Route::post('/merge',"Home\PageController@mergeChannelResult");
        });
        Route::prefix('/notepad')->group(function (){
            Route::get('/',"Home\PageController@notepadList");
            Route::get('/torun/{id}/{token}',"Home\PageController@notepadRun");
            Route::get('/type/{id}',"Home\PageController@notepadListByType");
            Route::get('/toread/{id}/{token}',"Home\PageController@notepadRead");
            Route::get('/readnote/{id}/{token}',"Home\PageController@notepadReadMessage");
        });
        Route::prefix("/user")->group(function (){
            Route::get('/adduser',"Home\UserController@addIndex");
            Route::get('/userlist',"Home\UserController@userList");
            Route::get('/adminlist',"Home\UserController@adminList");
            Route::post('/adduser',"Home\UserController@addUser");
            Route::get('/addadmin',"Home\UserController@addAdminIndex");
            Route::post('/addadmin',"Home\UserController@addAdmin");
            Route::get('/dumpuser',"Home\UserController@dumpUser");
            Route::get('/dumpadmin',"Home\UserController@dumpAdmin");
            Route::post('/dumpuser',"Home\UserController@showDumpUserList");
            Route::get('/getdumpuser',"Home\UserController@dumpUserList");
            Route::post('/dumpadmin',"Home\UserController@dumpAdminList");
            Route::post('/search',"Home\UserController@search");
            Route::get('/search/type',"Home\UserController@getUserListByType");
            Route::get('/search/username',"Home\UserController@getUserListByUsername");
            Route::get('/ajaxsearch/username',"Home\UserController@getAjaxUserListByUsername");
            Route::get('/search/addr',"Home\UserController@getUserListByAddr");
            Route::get('/search/age',"Home\UserController@getUserListByAge");
            Route::get('/search/tel',"Home\UserController@getUserListByTel");
            Route::get('/search/time',"Home\UserController@getUserListByTime");
            Route::get('/show',"Home\UserController@getUserListForSelect");
            Route::get('/userinfo/{id}/{token}',"Home\UserController@getUserInfoById");
            Route::get('/userinfodata/{id}/{token}',"Home\UserController@getUserInfoByIdData");
            Route::get('/del/{id}/{token}',"Home\UserController@userDel");
            Route::get('/departmentlist',"Home\UserController@departmentList");
            Route::post('/departmentsearch',"Home\UserController@departmentSearch");
            Route::get('/channellist',"Home\UserController@channelList");
            Route::post('/channelsearch',"Home\UserController@channelSearch");
            Route::post('/custome','Home\UserController@findCustomUser');
            Route::get('/custome','Home\UserController@findCustomUserMessage');
            Route::get('/customeinfo','Home\UserController@findCustomUserInfo');
        });
        Route::prefix("/wechat")->group(function (){
            Route::get("/del/{id}/{token}",'Admin\WechatController@wechatDel');
            Route::get("/list",'Admin\WechatController@wechatList');
            Route::get("/conf/{id}/{token}",'Admin\WechatController@wechatEdit');
            Route::get("/conf",'Admin\WechatController@wechatAdd');
            Route::post("/conf/{id}/{token}",'Admin\WechatController@wechatEdit');
            Route::post("/conf",'Admin\WechatController@wechatAdd');
            Route::post("/getconf/{token}",'Admin\WechatController@getWechatToken');
            Route::prefix("/keywords")->group(function (){
                Route::get("/list","Admin\WechatController@keyWordList");
                Route::get("/add","Admin\WechatController@keyWordAdd");
                Route::post("/add","Admin\WechatController@keyWordAddResult");
                Route::get("/edit/{id}/{token}","Admin\WechatController@keyWordEdit");
                Route::post("/edit/{id}/{token}","Admin\WechatController@keyWordEditResult");
                Route::get("/del/{id}/{token}","Admin\WechatController@keyWordDel");
            });
            Route::prefix("/games")->group(function (){
                Route::get("/list","Admin\WechatController@gamesList");
                Route::get("/add","Admin\WechatController@gamesAdd");
                Route::post("/add","Admin\WechatController@gamesAddResult");
                Route::get("/edit/{id}","Admin\WechatController@gamesEdit");
                Route::post("/edit/{id}","Admin\WechatController@gamesEditResult");
                Route::get("/del/{id}","Admin\WechatController@gamesDel");
            });
            Route::prefix("/question")->group(function (){
                Route::get("/","Admin\WechatController@questionList");
                Route::get("/add","Admin\WechatController@questionAdd");
                Route::post("/add","Admin\WechatController@questionAddResult");
                Route::get("/show/{id}/{token}","Admin\WechatController@questionShow");
                Route::get("/edit/{id}/{token}","Admin\WechatController@questionEdit");
                Route::post("/edit/{id}","Admin\WechatController@questionEditResult");
                Route::get("/del/{id}/{token}","Admin\WechatController@questionDel");
            });
        });
        Route::prefix("/custom")->group(function (){
            Route::get("/outhis","Home\CustomController@customOutHisList");
            Route::get("/add","Home\CustomController@customAdd");
            Route::post("/add","Home\CustomController@customAddResult");
            Route::get("/list","Home\CustomController@customList");
            Route::get("/del/{id}/{token}","Home\CustomController@customDel");
            Route::get("/returnadd","Home\CustomController@customReturnAdd");
            Route::post("/returnadd","Home\CustomController@customReturnAddResult");
            Route::get("/returnlist","Home\CustomController@customReturnList");
            Route::get("/returndel/{id}/{token}","Home\CustomController@customReturnDel");
            Route::post("/search","Home\CustomController@customSearch");
            Route::get('/search/{type}/username',"Home\CustomController@getCustomListByUsername");
            Route::get('/search/{type}/time',"Home\CustomController@getCustomListByTime");
            Route::get('/search/{type}/tel',"Home\CustomController@getCustomListByTel");
            Route::get('/search/{type}/bednumber',"Home\CustomController@getCustomListByBedNumber");
            Route::get('/search/{type}/hisnumber',"Home\CustomController@getCustomListByHisNumber");
            Route::get('/outtime/search/{type}/username',"Home\CustomController@getCustomListByUsernameOutTime");
            Route::get('/outtime/search/{type}/time',"Home\CustomController@getCustomListByTimeOutTime");
            Route::get('/outtime/search/{type}/tel',"Home\CustomController@getCustomListByTelOutTime");
            Route::get('/outtime/search/{type}/bednumber',"Home\CustomController@getCustomListByBedNumberOutTime");
            Route::get('/outtime/search/{type}/hisnumber',"Home\CustomController@getCustomListByHisNumberOutTime");
            Route::get('/searchclass/{type}/{id}',"Home\CustomController@getCustomListByClass");
            Route::get('/addc/{id}/{token}',"Home\CustomController@addCustomMessage");
            Route::get('/showh/{id}/{token}',"Home\CustomController@showCustomMessage");
            Route::get('/show',"Home\CustomController@showCustomListMessage");
            Route::get('/leaveh/{id}/{token}',"Home\CustomController@leaveHospital");
            Route::post('/addc',"Home\CustomController@addCustomMessageResult");
            Route::get('/addcl/{id}/{token}',"Home\CustomController@editCustomMessage");
            Route::post('/addcl',"Home\CustomController@editCustomMessageResult");
            Route::prefix("/appointment")->group(function (){
                Route::get('/',"Home\CustomController@customAppointmentUser");
                Route::get('/del/{id}/{token}',"Home\CustomController@customAppointmentUserDel");
                Route::post('/add',"Home\CustomController@customAppointmentUserAddResult");
                Route::get('/add',"Home\CustomController@customAppointmentUserAdd");
                Route::get('/show/{id}',"Home\CustomController@customAppointmentUserShow");
                Route::get('/edit/{id}',"Home\CustomController@customAppointmentUserEdit");
                Route::post('/edit/{id}',"Home\CustomController@customAppointmentUserEditResult");
                Route::get('/search',"Home\CustomController@customAppointmentSearch");
            });

        });
    });
    Route::prefix("/login")->group(function (){
        Route::get("/","Home\LoginController@index");
        Route::post("/","Home\LoginController@doLogin");
        Route::get("/logout","Home\LoginController@logout");
    });
});
/**
 * 微信模块
 */
Route::prefix("wechat/{wechat_id}")->group(function (){
    Route::prefix("/service")->group(function (){
        Route::any('/token','Admin\WechatController@token');
        Route::any('/redirect','Admin\WechatController@wechatRedirectUri');
    });
    Route::prefix("/admin")->middleware(['login'])->group(function (){
        Route::get("/","Wechat\AdminController@index");
        Route::prefix("/keywords")->group(function (){
            Route::get("/","Wechat\AdminController@keyWordList");
            Route::post("/uploadMaterial","Wechat\AdminController@uploadMaterial");
            Route::get("/add","Wechat\AdminController@keyWordAdd");
            Route::post("/add","Wechat\AdminController@keyWordAddResult");
            Route::get("/edit/{id}/{token}","Wechat\AdminController@keyWordEdit");
            Route::post("/edit/{id}/{token}","Wechat\AdminController@keyWordEditResult");
            Route::get("/del/{id}/{token}","Wechat\AdminController@keyWordDel");
        });
        Route::prefix("/games")->group(function (){
            Route::get("/list","Wechat\AdminController@gamesList");
            Route::get("/add","Wechat\AdminController@gamesAdd");
            Route::post("/add","Wechat\AdminController@gamesAddResult");
            Route::get("/edit/{id}","Wechat\AdminController@gamesEdit");
            Route::post("/edit/{id}","Wechat\AdminController@gamesEditResult");
            Route::get("/del/{id}","Wechat\AdminController@gamesDel");
        });
        Route::prefix("/question")->group(function (){
            Route::get("/","Wechat\AdminController@questionList");
            Route::get("/add","Wechat\AdminController@questionAdd");
            Route::post("/add","Wechat\AdminController@questionAddResult");
            Route::get("/addtips/{id}/{token}","Wechat\AdminController@questionAddTips");
            Route::post("/addtips","Wechat\AdminController@questionAddTipsResult");
            Route::get("/addcode/{id}/{token}","Wechat\AdminController@questionAddCode");
            Route::post("/addcode","Wechat\AdminController@questionAddCodeResult");
            Route::get("/show/{id}/{token}","Wechat\AdminController@questionShow");
            Route::get("/edit/{id}/{token}","Wechat\AdminController@questionEdit");
            Route::post("/edit","Wechat\AdminController@questionEditResult");
            Route::get("/del/{id}/{token}","Wechat\AdminController@questionDel");
        });
        Route::prefix("/menu")->group(function (){
            Route::get("/","Wechat\AdminController@menuList");
            Route::get("/add","Wechat\AdminController@menuAdd");
            Route::post("/add","Wechat\AdminController@menuAddResult");
            Route::get("/show/{id}/{token}","Wechat\AdminController@menuShow");
            Route::get("/edit/{id}/{token}","Wechat\AdminController@menuEdit");
            Route::post("/edit/{id}","Wechat\AdminController@menuEditResult");
            Route::get("/del/{id}/{token}","Wechat\AdminController@menuDel");
        });
    });
    Route::prefix("/games")->group(function (){
        Route::any('token','Admin\WechatController@token');
    });
    Route::prefix("/question")->group(function (){
        Route::get('/','Wechat\QuestionController@loadPage');
        Route::get('/page/{id}/{token}','Wechat\QuestionController@loadQuestionPage');
        Route::post('/add','Wechat\QuestionController@submitQuertion');
    });
    Route::prefix("/mall")->group(function (){
        Route::get("/","Wechat\MallController@indexPage");
        Route::get("/cate","Wechat\MallController@catePage");
        Route::get("/cart","Wechat\MallController@cartPage");
        Route::get("/user","Wechat\MallController@userPage");
    });
    Route::prefix("/user")->group(function (){
        Route::any('token/{id}','Admin\WechatController@token');
    });
});
Route::prefix("/custom")->group(function (){
    Route::get("/",'Home\CustomController@index');
});