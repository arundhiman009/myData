<?php

use App\Http\Controllers\UserController;
use App\Http\Controllers\GameController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PromoCodesController;
// use App\Http\Controllers\UserWalletController;
use App\Http\Controllers\SocialLoginController;
use App\Http\Controllers\SlotCreationController;
// use App\Http\Controllers\WithdrawMoneyController;
use App\Http\Controllers\CashoutRequestController;
use App\Http\Controllers\UserSocialShareController;
use App\Http\Controllers\CashoutLocationController;
use App\Http\Controllers\LoadMoneyRequestController;

use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

// use App\Http\Controllers\Cashier\DashboardController as CashierDashboard;


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

Route::get('logout', function () {
    return abort('404');
});

Route::post('/pusher/auth',[ChatController::class, 'pusherAuth'])->name('pusher.auth');

Route::get('/', [HomeController::class, 'index'])->name('home.page');

Route::get('login/{service?}',[SocialLoginController::class,'redirectToProvider']);
Route::get('login/{service}/callback', [SocialLoginController::class,'handleProviderCallback'])->name('social.login.callback');

Route::group(['middleware' => ['auth:sanctum', 'verified']], function () {
  Route::get('/admin/verify', [HomeController::class, 'adminApproval'])->name('admin.verify');
});
Route::group(['middleware' => ['auth:sanctum', 'verified', 'approval']], function () {

  Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
  Route::get('update-password', [DashboardController::class, 'updatePassword'])->name('update.password');
  Route::get('profile', [UserController::class, 'profile'])->name('profile');
  Route::post('update-profile', [UserController::class, 'updateProfile'])->name('update-profile');
  // Route::match(['GET','POST'],'load-money-approve/{orderId}', [PaypalController::class, 'getOrderDetails'])->name('paypal.order.details');
  
  // Route::get('withdraw-money', [WithdrawMoneyController::class, 'getMoney'])->name('withdraw.money');
  Route::get('social-share', [UserSocialShareController::class, 'index'])->name('social.share');
  Route::post('social-share', [UserSocialShareController::class, 'sendInvitation'])->name('social.share.email');
  Route::post('social-share', [UserSocialShareController::class, 'sendInvitation'])->name('social.share.email');

  // chat
  Route::get('/chat',[ChatController::class, 'index'])->name('chat.index');
  Route::post('user-chat-list',[ChatController::class,'userChatList'])->name('chat.list');
  Route::get('/get-messages', [ChatController::class,'fetchMessages'])->name('chat.fetchMessages');
  Route::get('/get-group-messages', [ChatController::class,'fetchGroupMessages'])->name('chat.fetchGroupMessages');
  Route::post('/send-message', [ChatController::class,'sendMessage'])->name('chat.sendMessage');
  Route::post('user-messgaeCount',[ChatController::class, 'messageCount'])->name('user.messgaeCount');

  // Routes for creating load money request
  Route::get('/load-money/{type}', [LoadMoneyRequestController::class, 'create'])->name('load.money');
  Route::post('/check-promo-code', [LoadMoneyRequestController::class, 'checkPromoCode'])->name('load.money.promo');
  Route::post('/load-money-submit', [LoadMoneyRequestController::class, 'store'])->name('load.money.submit');
  Route::get('/load_money_success', [LoadMoneyRequestController::class, 'storeSuccess'])->name('load.money.success');
  // Listing of Load money request
  Route::get('/load-money-request', [LoadMoneyRequestController::class, 'index'])->name('admin.loadmoney.request');
  Route::post('/load-money-request-list', [LoadMoneyRequestController::class, 'loadMoneyList'])->name('admin.loadmoney.list');  
  Route::post('/load-money-offline-submit', [LoadMoneyRequestController::class, 'saveOfflineRequest'])->name('load.money.offline.submit');
 

  // Routes for cashout request
  Route::post('/get-location-slots', [CashoutRequestController::class, 'getLocationSlots'])->name('cashout.slots');
  Route::post('/cashout-request-store', [CashoutRequestController::class, 'store'])->name('cashout.store');
  Route::post('/cashout-request-delete', [CashoutRequestController::class, 'delete'])->name('cashout.delete');
  Route::get('/cashout-request', [CashoutRequestController::class, 'index'])->name('cashout.index');
  Route::post('/cashout-request-list', [CashoutRequestController::class, 'list'])->name('cashout.list');
});

Route::group(['middleware' => ['auth:sanctum','verified','admincashier']],function() {
  //User Listing
  Route::get('users', [UserController::class, 'index'])->name('admin.users');
  Route::post('user-list', [UserController::class, 'userlist'])->name('admin.userlist');
  Route::post('loadmoney-update-status', [LoadMoneyRequestController::class, 'changeStatus'])->name('admin.loadmoney.status');

  Route::post('cashout-request-update-status', [CashoutRequestController::class, 'updateRequestStatus'])->name('admin.cashout.status');

  Route::get('/load-money-report', [LoadMoneyRequestController::class, 'indexReport'])->name('admin.loadmoney.report');
  Route::post('/load-money-report-list', [LoadMoneyRequestController::class, 'loadMoneyReportList'])->name('admin.loadmoney.report.list');
  Route::post('/load-money-cashier-request-send', [LoadMoneyRequestController::class, 'cashierRequestSend'])->name('cashier.send.request');
  Route::post('/load-money-admin-request-accept', [LoadMoneyRequestController::class, 'adminRequestAccept'])->name('admin.accept.request');
});


Route::group(['middleware' => ['auth:sanctum','verified','admin']],function() {

  //slot creation
  Route::get('slot-creation', [SlotCreationController::class, 'index'])->name('admin.slot-creation');
  Route::post('save-cashout-slot', [SlotCreationController::class, 'saveslot'])->name('admin.saveslot');
  Route::post('list-cashout-slot', [SlotCreationController::class, 'slotlist'])->name('admin.slotlist');
  Route::post('delete-cashout-slot', [SlotCreationController::class, 'deleteslot'])->name('admin.deleteslot');
  Route::post('get-cashout-slot', [SlotCreationController::class, 'getslot'])->name('admin.getslot');
  Route::post('update-cashout-slot', [SlotCreationController::class, 'updateslot'])->name('admin.updateslot');
  
  //Promo creation
  Route::get('promo-creation', [PromoCodesController::class, 'index'])->name('admin.promo-creation');
  Route::post('save-promo-code', [PromoCodesController::class, 'savepromocode'])->name('admin.savepromocode');
  Route::post('list-promo-code', [PromoCodesController::class, 'promocodelist'])->name('admin.promocodelist');
  Route::post('get-promo', [PromoCodesController::class, 'getpromo'])->name('admin.getpromo');
  Route::post('update-promocode', [PromoCodesController::class, 'updatepromo'])->name('admin.updatepromo');
  Route::post('delete-promocode', [PromoCodesController::class, 'deletepromocode'])->name('admin.deletepromocode');

  //game section
  Route::get('games', [GameController::class, 'index'])->name('admin.games');
  Route::post('gamelist', [GameController::class, 'gameslist'])->name('admin.gamelist');
  Route::post('deletegame', [GameController::class, 'gamedelete'])->name('admin.deletegame');
  Route::post('savegame', [GameController::class, 'savegame'])->name('admin.savegame');
  Route::post('getgame', [GameController::class, 'getgame'])->name('admin.getgame');
  Route::post('updategame', [GameController::class, 'updategame'])->name('admin.updategame');

  //Cashout Location
  Route::get('cashout-location', [CashoutLocationController::class, 'index'])->name('admin.cashout');
  Route::post('save-cashout-location', [CashoutLocationController::class, 'savelocation'])->name('admin.savelocation');
  Route::post('list-cashout-location', [CashoutLocationController::class, 'locationlist'])->name('admin.locationlist');
  Route::post('update-cashout-location', [CashoutLocationController::class, 'updatelocation'])->name('admin.updatelocation');
  Route::post('get-cashout-location', [CashoutLocationController::class, 'getlocation'])->name('admin.getlocation');
  Route::post('delete-cashout-location', [CashoutLocationController::class, 'deletelocation'])->name('admin.deletelocation');

  //Cashier Listing
  Route::get('cashier', [CashierController::class, 'index'])->name('admin.cashier');
  Route::post('cashier-list', [CashierController::class, 'cashierlist'])->name('admin.cashierlist');
  Route::post('addcashier', [CashierController::class, 'addcashier'])->name('admin.addcashier');
  Route::get('getCashier',[CashierController::class, 'getCashier'])->name('getCashier');
  Route::Post('CashierApprove',[CashierController::class, 'CashierApproveStatus'])->name('CashierApproved');  

  //User Listing
  Route::get('getAllCashier',[UserController::class, 'getAllCashier'])->name('CashierApprove');
  Route::post('cashierAssign',[UserController::class, 'cashierAssign'])->name('cashierAssign');
  Route::get('user-view', [UserController::class, 'userView'])->name('admin.userview');
  Route::post('user-view-permission', [UserController::class, 'userlistPermission'])->name('admin.userview-permission');
  Route::get('new-user', [UserController::class, 'newUser'])->name('admin.new-users');
  Route::post('create-users', [UserController::class, 'createUser'])->name('admin.create-users');
  Route::post('assigntouser', [UserController::class, 'assigntouser'])->name('assigntouser');
  Route::get('getUser',[UserController::class, 'getUser'])->name('getUser');
  Route::post('adminApprove',[UserController::class, 'adminApprove'])->name('admin_approve');
  // Route::post('assignPermissionUser',[UserController::class, 'assignPermissionUser'])->name('assignPermissionUser');

  // Admin settings
  Route::get('settings', [SettingController::class, 'index'])->name('admin.settings');
  Route::post('settings', [SettingController::class, 'socialInfo'])->name('admin.settings.info');
});