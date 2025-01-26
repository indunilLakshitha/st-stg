<?php

use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Admin\CommissionController;
use App\Http\Controllers\Admin\CourseCategoryController;
use App\Http\Controllers\Admin\CourseController as AdminCourseController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DepositedListController;
use App\Http\Controllers\Admin\ForgotPasswordController;
use App\Http\Controllers\Admin\OrderHistoryController;
use App\Http\Controllers\Admin\SalesReportController;
use App\Http\Controllers\Admin\SettingControlelr;
use App\Http\Controllers\Admin\TeamViewController;
use App\Http\Controllers\AdminPasswordManagerController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CoursePaymentController;
use App\Http\Controllers\CustomerRegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IndexPageController;
use App\Http\Controllers\MyTeamController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReferralController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\SalesChartController;
use App\Http\Controllers\Test\TestController;
use App\Http\Controllers\TreeViewController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});

/**
 * User Regoster Section
 */
Route::get('/register', [IndexPageController::class, 'register'])->name('register');
Route::get('/register/{referral_id}', [IndexPageController::class, 'coursesWithReferrel'])->name('home.courseWithRef');
Route::get('/register/{referral_id}/{course_id}', [IndexPageController::class, 'registerWithRef'])->name('home.registerWithRef');
Route::get('/checkout/{user_id}', [IndexPageController::class, 'checkout'])->name('home.checkout');

Route::get('/', [IndexPageController::class, 'index'])->name('index');
Route::get('/courses', [IndexPageController::class, 'courses'])->name('courses');
Route::get('/courses/{id}/register', [IndexPageController::class, 'regWithcourse'])->name('regWithcourse');


/**
 * basic Pages Section
 */
Route::get('/about-us', [IndexPageController::class, 'aboutUs'])->name('aboutUs');
Route::get('/our-team', [IndexPageController::class, 'ourTeam'])->name('ourTeam');
Route::get('/contact-us', [IndexPageController::class, 'contactUs'])->name('contactUs');
Route::get('/thank-you/{user_id}', [IndexPageController::class, 'thankYou'])->name('thankYou');
Route::get('/terms-and-conditions', [IndexPageController::class, 'termsNConditn'])->name('tNc');
Route::get('/privacy-policy', [IndexPageController::class, 'pp'])->name('pp');

/**
 * Forgot Password Section
 */
Route::prefix('forgot-password')->name('forgotPassword.')->group(function () {
    Route::post('/notify', [ForgotPasswordController::class, 'notify'])->name('notify');
    Route::get('/verify-otp/{ref_no}', [ForgotPasswordController::class, 'verifyView'])->name('verifyView');
    Route::post('/verify', [ForgotPasswordController::class, 'verify'])->name('verify');
    Route::get('/change/{ref_id}', [ForgotPasswordController::class, 'changeView'])->name('changeView');
    Route::post('/change', [ForgotPasswordController::class, 'change'])->name('change');
});


Route::middleware(['auth:web', 'noindex'])->prefix('dashboard')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('admin.dashboard');

    Route::prefix('course')->name('course.')->group(function () {
        Route::get('/', [CourseController::class, 'index'])->name('index');
        Route::get('/my', [CourseController::class, 'myCourses'])->name('myCourses');
        Route::get('/{course_id}', [CourseController::class, 'view'])->name('view');
        Route::get('/{course_id}/pay/{method}/{type}', [CoursePaymentController::class, 'pay'])->name('pay');
    });

    Route::prefix('user')->name('user.')->group(function () {
        Route::get('/list', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
    });

    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'index'])->name('index');
        Route::get('/create', [ProfileController::class, 'create'])->name('create');
    });

    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/history', [OrderController::class, 'history'])->name('history');
        Route::get('/history/marketplace', [OrderController::class, 'marketHistoristory'])->name('marketHistory');
    });

    Route::prefix('my')->name('my.')->group(function () {
        Route::get('/tree', [TreeViewController::class, 'my'])->name('tree');
        Route::get('/team', [MyTeamController::class, 'my'])->name('team');
        Route::get('/sales', [SalesChartController::class, 'sales'])->name('sales');
        Route::get('/pending-activations', [SalesChartController::class, 'pendingActivations'])->name('pendingActivations');
    });

    Route::prefix('referrals')->name('referrals.')->group(function () {
        Route::get('/pending', [ReferralController::class, 'pending'])->name('pending');
        Route::get('/approve/{id}', [ReferralController::class, 'approve'])->name('approve');
    });

    Route::prefix('wallet')->name('wallet.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
    });

    Route::prefix('report')->name('report.')->group(function () {
        Route::get('/', [ReportController::class, 'index'])->name('index');
    });

    Route::middleware(['is_admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::prefix('customer')->name('customer.')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('index');
            Route::get('/create', [CustomerController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
            Route::get('/requests', [CustomerController::class, 'requests'])->name('requests');
            Route::get('/registration-requests', [CustomerController::class, 'registrationRequests'])->name('registrationRequests');
        });

        Route::prefix('course')->name('course.')->group(function () {
            Route::get('/', [AdminCourseController::class, 'index'])->name('index');
            Route::get('/my', [AdminCourseController::class, 'myCourses'])->name('myCourses');
            Route::get('/create', [AdminCourseController::class, 'create'])->name('create');
            Route::get('/edit/{id}', [AdminCourseController::class, 'edit'])->name('edit');

            Route::prefix('category')->name('category.')->group(function () {
                Route::get('/', [CourseCategoryController::class, 'index'])->name('index');
                Route::get('/create', [CourseCategoryController::class, 'create'])->name('create');
                Route::get('/edit/{id}', [CourseCategoryController::class, 'edit'])->name('edit');
            });
        });

        Route::prefix('user')->name('user.')->group(function () {
            Route::get('/list', [AdminUserController::class, 'index'])->name('index');
            Route::get('/create', [AdminUserController::class, 'ceate'])->name('create');
            Route::get('/edit/{id}', [AdminUserController::class, 'edit'])->name('edit');
        });

        Route::prefix('team')->name('team.')->group(function () {
            Route::get('/', [TeamViewController::class, 'index'])->name('index');
        });

        Route::prefix('sales')->name('sales.')->group(function () {
            Route::get('/', [SalesReportController::class, 'index'])->name('index');
        });

        Route::prefix('pasword')->name('pasword.')->group(function () {
            Route::get('/list', [AdminPasswordManagerController::class, 'index'])->name('list');
            Route::get('/edit/{id}', [AdminPasswordManagerController::class, 'edit'])->name('edit');
        });

        Route::prefix('commission')->name('commission.')->group(function () {
            Route::get('/list', [CommissionController::class, 'index'])->name('index');
            Route::get('/generate', [CommissionController::class, 'generate'])->name('generate');
        });

        Route::prefix('deposited')->name('deposited.')->group(function () {
            Route::get('/', [DepositedListController::class, 'index'])->name('index');
            Route::get('/pending', [DepositedListController::class, 'pending'])->name('pending');
        });
        Route::prefix('order')->name('order.')->group(function () {
            Route::get('/history', [OrderHistoryController::class, 'history'])->name('history');
        });

        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [SettingControlelr::class, 'settings'])->name('settings');
        });
    });
});

// Route::get('/test', [TestController::class, 'testPointAdding']);
// Route::get('/test/sms-test', [TestController::class, 'sendOTP']);
// Route::get('/test/sms-test/verify/{otp}/{ref}', [TestController::class, 'verifyOTP']);
// Route::get('/test/sms-test/sendMSG', [TestController::class, 'sendMSG']);
// Route::get('/trnsfer/transferDummyCommissions', [TestController::class, 'transferDummyCommission']);
// Route::get('/fix/commissions', [TestController::class, 'fixCommission']);
Route::get('/fix/points', [TestController::class, 'fixPoints']);
