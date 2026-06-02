<?php

use App\Http\Controllers\Admin\AdminDeliveryController;
use App\Http\Controllers\Admin\AdminInOrderController;
use App\Http\Controllers\Admin\AdminInPOController;
use App\Http\Controllers\Admin\AdminProfileController;
use App\Http\Controllers\Admin\BarangMasukController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\RekapPenjualanController;
use App\Http\Controllers\Admin\ZoneController;
use App\Http\Controllers\AdminGudang\IncOrdersController;
use App\Http\Controllers\AdminGudang\ProfileGudangController;
use App\Http\Controllers\Auth\Register;
use App\Http\Controllers\Customer\HomeController as CustomerHomeController;
use App\Http\Controllers\Admin\KoordinatorSalesController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\AdminGudang\HomeController as AdminGudangHomeController;
use App\Http\Controllers\AdminGudang\VerifikasiStockController;
use App\Http\Controllers\Auth\Login;
use App\Http\Controllers\Auth\Logout;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\PurchaseOrderController;
use App\Http\Controllers\Direktur\DirekturHomeController;
use App\Http\Controllers\Direktur\ProfileController;
use App\Http\Controllers\Direktur\ReportController;
use App\Http\Controllers\Direktur\VerificationOrderController;
use App\Http\Controllers\Direktur\VerificationPOController;
use App\Http\Controllers\Driver\DriverHomeController;
use App\Http\Controllers\KoorSales\KoorHomeController;
use App\Http\Controllers\KoorSales\KoorIncoOrderController;
use App\Http\Controllers\KoorSales\KoorIncoPOController;
use App\Http\Controllers\KoorSales\KoorProductController;
use App\Http\Controllers\Sales\IncomingOrderController;
use App\Http\Controllers\Sales\IncomingPOController;
use App\Http\Controllers\Sales\SalesController as SalesHomeController;
use App\Http\Controllers\Sales\SalesOrderController;
use App\Http\Controllers\Sales\SalesPOController;
use App\Http\Controllers\Sales\SalesProductController;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     return view('admin.home');
// })->middleware('auth');


Route::view('/login', 'auth.login')->name('login')->middleware('guest');
Route::get('/register', [Register::class, 'showRegistrationForm'])->name('register')->middleware('guest');
Route::post('/register', [Register::class, 'register'])->name('post.register')->middleware('guest');
Route::post('/login', Login::class)->name('post.login');
Route::post('/logout', Logout::class)->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::middleware(['role:admin_kantor'])->group(function () {
        Route::get('/', [HomeController::class, 'index'])->name('admin.home');
        Route::post('/admin/orders/{id}/mark-as-paid', [HomeController::class, 'markAsPaid'])->name('admin.orders.mark-as-paid');
        Route::get('/admin/products', [ProductController::class, 'index'])->name('products.index');
        Route::get('/json/products', [ProductController::class, 'productJson'])->name('products.json');
        Route::get('/admin/products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('/admin/products', [ProductController::class, 'store'])->name('products.store');
        Route::get('/admin/products/{id}/edit', [ProductController::class, 'edit'])->name('products.edit');
        Route::put('/admin/products/{id}', [ProductController::class, 'update'])->name('products.update');
        Route::delete('/admin/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::get('/admin/customers', [CustomerController::class, 'index'])->name('admin.customers.index');
        Route::get('/admin/sales', [SalesController::class, 'index'])->name('admin.sales.index');
        Route::get('/admin/sales/create', [SalesController::class, 'create'])->name('admin.sales.create');
        Route::post('/admin/sales', [SalesController::class, 'store'])->name('admin.sales.store');
        Route::put('/admin/sales/{id}', [SalesController::class, 'update'])->name('admin.sales.update');
        Route::delete('/admin/sales/{id}', [SalesController::class, 'destroy'])->name('admin.sales.destroy');
        Route::get('/admin/koordinator-sales', [KoordinatorSalesController::class, 'index'])->name('admin.koordinator.index');
        Route::get('/admin/koordinator-sales/create', [KoordinatorSalesController::class, 'create'])->name('admin.koordinator.create');
        Route::post('/admin/koordinator-sales', [KoordinatorSalesController::class, 'store'])->name('admin.koordinator.store');
        Route::put('/admin/koordinator-sales/{id}', [KoordinatorSalesController::class, 'update'])->name('admin.koordinator.update');
        Route::delete('/admin/koordinator-sales/{id}', [KoordinatorSalesController::class, 'destroy'])->name('admin.koordinator.destroy');
        Route::get('/barang-masuk', [BarangMasukController::class, 'index'])->name('admin.barang-masuk.index');
        Route::get('/barang-masuk/create', [BarangMasukController::class, 'create'])->name('admin.barang-masuk.create');
        Route::post('/barang-masuk', [BarangMasukController::class, 'store'])->name('admin.barang-masuk.store');
        Route::get('/barang-masuk/{id}/edit', [BarangMasukController::class, 'edit'])->name('admin.barang-masuk.edit');
        Route::put('/barang-masuk/{id}', [BarangMasukController::class, 'update'])->name('admin.barang-masuk.update');
        Route::put('/barang-masuk/reset/{reference}', [BarangMasukController::class, 'resetVerification'])
            ->name('admin.barang-masuk.reset')
            ->where('reference', '.*');
        Route::put('/barang-masuk/update-stock/{reference}', [BarangMasukController::class, 'updateStock'])
            ->name('admin.barang-masuk.update-stock')
            ->where('reference', '.*');
        Route::get('/admin/orders', [AdminInOrderController::class, 'index'])->name('admin.incorders.index');
        Route::get('/admin/orders/{id}/preview', [AdminInOrderController::class, 'previewInvoice'])->name('admin.incorders.preview');
        Route::post('/admin/orders/{id}/approve', [AdminInOrderController::class, 'approve'])->name('admin.incorders.approve');
        Route::post('/admin/orders/{id}/update-details', [AdminInOrderController::class, 'updateDetails'])->name('admin.incorders.update-details');
        Route::get('/admin/zones', [ZoneController::class, 'index'])->name('admin.zones.index');
        Route::post('/admin/zones', [ZoneController::class, 'store'])->name('admin.zones.store');
        Route::put('/admin/zones/{id}', [ZoneController::class, 'update'])->name('admin.zones.update');
        Route::delete('/admin/zones/{id}', [ZoneController::class, 'destroy'])->name('admin.zones.destroy');
        Route::get('/admin/purchase-orders', [AdminInPOController::class, 'index'])->name('admin.po.index');
        Route::post('/admin/purchase-orders/{id}/approve', [AdminInPOController::class, 'approve'])->name('admin.po.approve');
        Route::post('/admin/purchase-orders/{id}/update-details', [AdminInPOController::class, 'updateDetails'])->name('admin.po.update-details');
        Route::post('/admin/purchase-orders/{id}/update-status', [AdminInPOController::class, 'updateStatus'])->name('admin.po.update-status');
        Route::post('/admin/purchase-orders/{id}/convert', [AdminInPOController::class, 'convert'])->name('admin.po.convert');
        Route::get('/admin/purchase-orders/export/{status}', [AdminInPOController::class, 'exportExcel'])->name('admin.po.export');
        Route::get('/admin/purchase-orders/{id}/export', [AdminInPOController::class, 'exportSinglePO'])->name('admin.po.export-single');
        Route::get('/admin/deliveries', [AdminDeliveryController::class, 'index'])->name('admin.deliveries.index');
        Route::get('/admin/deliveries/{id}/surat-jalan', [AdminDeliveryController::class, 'previewSuratJalan'])->name('admin.deliveries.surat-jalan');
        Route::post('/admin/deliveries/{id}/to-gudang', [AdminDeliveryController::class, 'updateStatusToGudang'])->name('admin.deliveries.to-gudang');
        Route::get('/admin/profile', [AdminProfileController::class, 'index'])->name('admin.profile.index');
        Route::post('/admin/profile/update-signature', [AdminProfileController::class, 'updateSignature'])->name('admin.profile.update-signature');
        Route::get('/admin/rekap-penjualan', [RekapPenjualanController::class, 'index'])->name('admin.rekap-penjualan.index');
        Route::post('/admin/rekap-penjualan/export', [RekapPenjualanController::class, 'export'])->name('admin.rekap-penjualan.export');
        Route::get('/admin/stock-logs', [\App\Http\Controllers\Admin\StockLogController::class, 'index'])->name('admin.stock-logs.index');
        Route::get('/admin/customers/{id}/json', [CustomerController::class, 'getCustomerJson'])->name('admin.customers.json');
    });
    // admin gudang
    Route::prefix('admin-gudang')->group(function () {
        Route::get('/', [AdminGudangHomeController::class, 'index'])->name('gudang.home');
        Route::get('/verifikasi-stock', [VerifikasiStockController::class, 'index'])->name('gudang.verifikasi.index');
        Route::post('/verifikasi-stock', [VerifikasiStockController::class, 'verify'])->name('gudang.verifikasi.submit');
        Route::get('/inc-orders', [IncOrdersController::class, 'index'])->name('gudang.incorders.index');
        Route::get('/inc-orders/{id}/surat-jalan', [IncOrdersController::class, 'previewSuratJalan'])->name('gudang.incorders.surat-jalan');
        Route::get('/inc-orders/drivers', [IncOrdersController::class, 'getDrivers'])->name('gudang.incorders.drivers');
        Route::post('/inc-orders/{id}/process-delivery', [IncOrdersController::class, 'processToDelivery'])->name('gudang.incorders.process-delivery');
        Route::post('/inc-orders/{id}/ready', [IncOrdersController::class, 'markAsReady'])->name('gudang.incorders.ready');
        Route::get('/stock-logs', [\App\Http\Controllers\AdminGudang\StockLogController::class, 'index'])->name('gudang.stock-logs.index');
        Route::get('/profile', [ProfileGudangController::class, 'index'])->name('gudang.profile.index');
        Route::post('/profile/update-signature', [ProfileGudangController::class, 'updateSignature'])->name('gudang.profile.update-signature');
    });

    // pelanggan
    Route::prefix('customers')->group(function () {
        Route::get('/', [CustomerHomeController::class, 'index'])->name('customers.home');
        Route::get('/orders', [OrderController::class, 'index'])->name('customers.order.index');
        Route::post('/orders', [OrderController::class, 'store'])->name('customers.order.store');
        Route::get('/purchase-orders', [PurchaseOrderController::class, 'index'])->name('customers.purchase-order.index');
        Route::post('/purchase-orders', [PurchaseOrderController::class, 'store'])->name('customers.purchase-order.store');
        Route::get('/products', [\App\Http\Controllers\Customer\ProductController::class, 'index'])->name('customers.products.index');
    });
    //sales
    Route::get('/sales', [SalesHomeController::class, 'index'])->name('sales.home');
    Route::get('/sales/incoming-orders', [IncomingOrderController::class, 'index'])->name('sales.incomingorders.index');
    Route::get('/sales/incoming-orders/{id}', [IncomingOrderController::class, 'show'])->name('sales.incomingorders.show');
    Route::put('/sales/incoming-orders/item/{id}', [IncomingOrderController::class, 'updateItem'])->name('sales.incomingorders.update-item');
    Route::post('/sales/incoming-orders/{id}/verify', [IncomingOrderController::class, 'verify'])->name('sales.incomingorders.verify');
    Route::post('/sales/incoming-orders/{id}/reject', [IncomingOrderController::class, 'reject'])->name('sales.incomingorders.reject');
    Route::get('/sales/incoming-po', [IncomingPOController::class, 'index'])->name('sales.incomingpo.index');
    Route::get('/sales/incoming-po/{id}', [IncomingPOController::class, 'show'])->name('sales.incomingpo.show');
    Route::post('/sales/incoming-po/item/{id}/update', [IncomingPOController::class, 'updateItem'])->name('sales.incomingpo.item.update');
    Route::post('/sales/incoming-po/{id}/verify', [IncomingPOController::class, 'verify'])->name('sales.incomingpo.verify');
    Route::post('/sales/incoming-po/{id}/reject', [IncomingPOController::class, 'reject'])->name('sales.incomingpo.reject');
    Route::get('/sales/products', [SalesProductController::class, 'index'])->name('sales.products.index');
    Route::get('/sales/order', [SalesOrderController::class, 'index'])->name('sales.order.index');
    Route::post('/sales/order', [SalesOrderController::class, 'store'])->name('sales.order.store');
    Route::get('/sales/purchase-order', [SalesPOController::class, 'index'])->name('sales.purchase-order.index');
    Route::post('/sales/purchase-order', [SalesPOController::class, 'store'])->name('sales.purchase-order.store');

    //koor sales
    Route::get('/koor-sales/home', [KoorHomeController::class, 'index'])->name('koor.sales.home');
    Route::get('/koor-sales/pesanan-masuk', [KoorIncoOrderController::class, 'index'])->name('koor.orders.index');
    Route::get('/koor-sales/pesanan-masuk/{id}', [KoorIncoOrderController::class, 'show'])->name('koor.orders.show');
    Route::post('/koor-sales/pesanan-masuk/{id}/verify', [KoorIncoOrderController::class, 'verify'])->name('koor.orders.verify');
    Route::post('/koor-sales/pesanan-masuk/{id}/reject', [KoorIncoOrderController::class, 'reject'])->name('koor.orders.reject');
    Route::get('/koor-sales/po-masuk', [KoorIncoPOController::class, 'index'])->name('koor.po.index');
    Route::get('/koor-sales/po-masuk/{id}', [KoorIncoPOController::class, 'show'])->name('koor.po.show');
    Route::post('/koor-sales/po-masuk/{id}/verify', [KoorIncoPOController::class, 'verify'])->name('koor.po.verify');
    Route::post('/koor-sales/po-masuk/{id}/reject', [KoorIncoPOController::class, 'reject'])->name('koor.po.reject');
    Route::get('/koor-sales/katalog-produk', [KoorProductController::class, 'index'])->name('koor.products.index');

    Route::prefix('direktur')->group(function () {
        Route::get('/home', [DirekturHomeController::class, 'index'])->name('direktur.home');
        Route::get('/verification-orders', [VerificationOrderController::class, 'index'])->name('direktur.verification.orders');
        Route::post('/verification-orders/{id}/revise', [VerificationOrderController::class, 'revise'])->name('direktur.verification.revise');
        Route::post('/verification-orders/{id}/approve', [VerificationOrderController::class, 'approve'])->name('direktur.verification.approve');
        Route::get('/verification-po', [VerificationPOController::class, 'index'])->name('direktur.verificationpo.index');
        Route::post('/verification-po/{id}/approve', [VerificationPOController::class, 'approve'])->name('direktur.verificationpo.approve');
        Route::post('/verification-po/{id}/reject', [VerificationPOController::class, 'reject'])->name('direktur.verificationpo.reject');
        Route::get('/products', [\App\Http\Controllers\Direktur\ProductController::class, 'index'])->name('direktur.products.index');
        Route::get('/profile', [ProfileController::class, 'index'])->name('direktur.profile.index');
        Route::get('/orders/{id}/preview', [VerificationOrderController::class, 'previewInvoiceFinal'])->name('direktur.invoice.preview');
        Route::post('/profile/signature', [ProfileController::class, 'updateSignature'])->name('direktur.profile.update-signature');
        Route::get('/purchase-orders/export/{status}', [VerificationPOController::class, 'exportExcelDirektur'])->name('direktur.po.export');
        Route::get('/purchase-orders/{id}/export', [VerificationPOController::class, 'exportSinglePO'])->name('direktur.po.export-single');
        Route::get('/report', [ReportController::class, 'index'])->name('direktur.report.index');
        Route::get('/report/export', [ReportController::class, 'export'])->name('direktur.report.export');
        Route::get('/stock-logs', [\App\Http\Controllers\Direktur\StockLogController::class, 'index'])->name('direktur.stock-logs.index');
    });


    Route::get('/driver/home', [DriverHomeController::class, 'index'])->name('driver.home');
    Route::post('/driver/delivery/{id}/status', [DriverHomeController::class, 'updateStatus'])->name('driver.delivery.update-status');
    Route::post('/driver/delivery/{id}/spb', [DriverHomeController::class, 'generateSpb'])->name('driver.delivery.spb');
});
