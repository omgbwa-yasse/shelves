<?php

use App\Http\Controllers\BulletinBoardAdminController;
use App\Http\Controllers\PDFController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\TaskStatusController;
use App\Http\Controllers\TaskTypeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\MailSendController;
use App\Http\Controllers\MailReceivedController;
use App\Http\Controllers\MailArchiveController;
use App\Http\Controllers\MailAttachmentController;
use App\Http\Controllers\MailContainerController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\BatchReceivedController;
use App\Http\Controllers\BatchSendController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\RecordAuthorController;
use App\Http\Controllers\RecordAttachmentController;
use App\Http\Controllers\RecordContainerController;
use App\Http\Controllers\activityCommunicabilityController;
use App\Http\Controllers\MailAuthorController;
use App\Http\Controllers\MailTransactionController;
use App\Http\Controllers\MailAuthorContactController;
use App\Http\Controllers\BuildingController;
use App\Http\Controllers\floorController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ShelfController;
use App\Http\Controllers\ContainerController;
use App\Http\Controllers\MailTypologyController;
use App\Http\Controllers\ContainerStatusController;
use App\Http\Controllers\ContainerPropertyController;
use App\Http\Controllers\ActivityController;
use App\Http\Controllers\SortController;
use App\Http\Controllers\RetentionController;
use App\Http\Controllers\LawController;
use App\Http\Controllers\LawArticleController;
use App\Http\Controllers\RetentionLawArticleController;
use App\Http\Controllers\retentionActivityController;
use App\Http\Controllers\CommunicabilityController;
use App\Http\Controllers\OrganisationController;
use App\Http\Controllers\OrganisationRoomController;
use App\Http\Controllers\OrganisationActivityController;
use App\Http\Controllers\TermCategoryController;
use App\Http\Controllers\TermEquivalentTypeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\TermController;
use App\Http\Controllers\TermTypeController;
use App\Http\Controllers\TermEquivalentController;
use App\Http\Controllers\TermRelatedController;
use App\Http\Controllers\TermTranslationController;
use App\Http\Controllers\RecordController;
use App\Http\Controllers\lifeCycleController;
use App\Http\Controllers\RecordChildController;
use App\Http\Controllers\RecordSupportController;
use App\Http\Controllers\CommunicationStatusController;
use App\Http\Controllers\CommunicationController;
use App\Http\Controllers\SearchCommunicationController;
use App\Http\Controllers\CommunicationRecordController;
use App\Http\Controllers\ReservationStatusController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\ReservationRecordController;
use App\Http\Controllers\RecordStatusController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SearchMailController;
use App\Http\Controllers\SearchReservationController;
use App\Http\Controllers\SearchdollyController;
use App\Http\Controllers\SearchRecordController;
use App\Http\Controllers\BatchMailController;
use App\Http\Controllers\MailPriorityController;
use App\Http\Controllers\DollyController;
use App\Http\Controllers\DollyMailTransactionController;
use App\Http\Controllers\BarcodeController;
use App\Http\Controllers\SlipStatusController;
use App\Http\Controllers\SlipRecordController;
use App\Http\Controllers\SlipRecordAttachmentController;
use App\Http\Controllers\SlipController;
use App\Http\Controllers\SlipContainerController;
use App\Http\Controllers\SlipRecordContainerController;
use App\Http\Controllers\MailActionController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserOrganisationRoleController;
use App\Http\Controllers\DollyActionController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\SearchMailFeedbackController;
use App\Http\Controllers\SearchSlipController;
use App\Http\Controllers\UserRoleController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\BulletinBoardController;
use App\Http\Controllers\EventAttachmentController;
use App\Http\Controllers\PostAttachmentController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BackupController;
use App\Http\Controllers\BackupFileController;
use App\Http\Controllers\BackupPlanningController;

Auth::routes();

Route::get('pdf/thumbnail/{id}', [PDFController::class, 'thumbnail'])->name('pdf.thumbnail');

Route::group(['middleware' => 'auth'], function () {
    Route::prefix('api')->group(function () {
        Route::get('/authors', [AuthorController::class, 'indexApi']);
        Route::post('/authors', [AuthorController::class, 'storeApi']);
        Route::get('/author-types', [AuthorController::class, 'authorTypesApi']);
    });

    Route::post('/switch-organisation', [OrganisationController::class, 'switchOrganisation'])->name('switch.organisation');
    Route::get('/', [mailReceivedController::class, 'index']);





    Route::middleware(['auth'])->prefix('bulletin-boards')->group(function () {
        Route::resource('/bulletin-board', BulletinBoardController::class)->names('bulletin-boards');
        Route::resource('bulletin-board.posts', PostController::class)->names('bulletin-boards.posts');
        Route::resource('bulletin-board.events', EventController::class)->names('bulletin-boards.events');

        Route::post('bulletin-board/{BulletinBoard}/events/{event}',[EventController::class, 'updateStatus'] )->name('bulletin-boards.events.updateStatus');
        Route::post('bulletin-board/{BulletinBoard}/events/{post}',[PostController::class, 'toggleStatus'] )->name('bulletin-boards.posts.toggleStatus');

        Route::get('/events/{event}/attachments', [EventAttachmentController::class, 'index'])->name('events.attachments.index');
        Route::get('/events/{event}/attachments/create', [EventAttachmentController::class, 'create'])->name('events.attachments.create');
        Route::post('/events/{event}/attachments', [EventAttachmentController::class, 'store'])->name('events.attachments.store');
        Route::get('/events/{event}/attachments/{attachment}', [EventAttachmentController::class, 'show'])->name('events.attachments.show');
        Route::delete('/events/{event}/attachments/{attachment}', [EventAttachmentController::class, 'destroy'])->name('events.attachments.destroy');
        Route::get('/events/{id}/preview', [EventAttachmentController::class, 'preview'])->name('events.attachments.preview');
        Route::get('/events/{id}/download', [EventAttachmentController::class, 'download'])->name('events.attachments.download');

        Route::get('/posts/{post}/attachments', [PostAttachmentController::class, 'index'])->name('posts.attachments.index');
        Route::get('/posts/{post}/attachments/create', [PostAttachmentController::class, 'create'])->name('posts.attachments.create');
        Route::post('/posts/{post}/attachments', [PostAttachmentController::class, 'store'])->name('posts.attachments.store');
        Route::get('/posts/{post}/attachments/{attachment}', [PostAttachmentController::class, 'show'])->name('posts.attachments.show');
        Route::delete('/posts/{post}/attachments/{attachment}', [PostAttachmentController::class, 'destroy'])->name('posts.attachments.destroy');
        Route::get('/posts/{id}/preview', [PostAttachmentController::class, 'preview'])->name('posts.attachments.preview');
        Route::get('/posts/{id}/download', [PostAttachmentController::class, 'download'])->name('posts.attachments.download');

        Route::get('/events/{event}/attachments/list', [EventAttachmentController::class, 'getAttachmentsList'])->name('events.attachments.list');
        Route::post('/events/{event}/attachments', [EventAttachmentController::class, 'ajaxStore'])->name('events.attachments.ajax.store');
        Route::delete('/events/{event}/attachments/{attachment}', [EventAttachmentController::class, 'ajaxDestroy'])->name('events.attachments.ajax.destroy');

        Route::get('/posts/{post}/attachments/list', [PostAttachmentController::class, 'getAttachmentsList'])->name('posts.attachments.list');
        Route::post('/posts/{post}/attachments', [PostAttachmentController::class, 'ajaxStore'])->name('posts.attachments.ajax.store');
        Route::delete('/posts/{post}/attachments/{attachment}', [PostAttachmentController::class, 'ajaxDestroy'])->name('posts.attachments.ajax.destroy');

        Route::get('/dashboard', [BulletinBoardController::class, 'dashboard'])->name('bulletin-boards.dashboard');
        Route::post('events/{event}/register', [EventController::class, 'register'])->name('bulletin-boards.events.register');
        Route::post('events/{event}/unregister', [EventController::class, 'unregister'])->name('bulletin-boards.events.unregister');

        Route::get('/my-posts', [BulletinBoardController::class, 'myPosts'])->name('bulletin-boards.my-posts');
        Route::get('/archives', [BulletinBoardController::class, 'archives'])->name('bulletin-boards.archives');

        Route::post('/{bulletinBoard}/archive', [BulletinBoardController::class, 'toggleArchive'])->name('bulletin-boards.toggle-archive');
        Route::prefix('/organisations')->name('organisations.')->group(function () {
            Route::post('/{bulletinBoard}/attach', [BulletinBoardController::class, 'attachOrganisation'])->name('bulletin-boards.attach');
            Route::delete('/{bulletinBoard}/detach/{organisation}', [BulletinBoardController::class, 'detachOrganisation'])->name('bulletin-boards.detach');
        });

    });




    Route::prefix('mails')->group(function () {
        Route::post('advanced', [SearchMailController::class, 'advanced'])->name('mails.advanced');
        Route::get('advanced/form', [SearchMailController::class, 'form'])->name('mails.advanced.form');
        Route::resource('authors.contacts', MailAuthorContactController::class)->names('author-contact');
        Route::resource('archives', MailArchiveController::class)->names('mail-archive');
        Route::resource('container', MailContainerController::class)->names('mail-container');
        Route::resource('send', MailSendController::class)->names('mail-send');
        Route::get('feedback', [SearchMailFeedbackController::class, 'index'])->name('mail-feedback');
        Route::resource('received', MailReceivedController::class)->names('mail-received');
        Route::resource('authors', MailAuthorController::class)->names('mail-author');
        Route::resource('file.attachment', MailAttachmentController::class)->names('mail-attachment');
        Route::resource('typologies', MailTypologyController::class);
        Route::get('archived', [MailArchiveController::class, 'archived'])->name('mails.archived');
        Route::resource('batch', BatchController::class)->names('batch');
        Route::resource('batches.mail', BatchMailController::class)->names('batch.mail');
        Route::resource('batch-received', BatchReceivedController::class)->names('batch-received');
        Route::resource('batch-send', BatchSendController::class)->names('batch-send');
        Route::get('batch-received/logs', [BatchReceivedController::class, 'logs'] )->name('batch-received-log');
        Route::get('batch-send/logs', [BatchSendController::class, 'logs'] )->name('batch-send-log');
        Route::post('mail-transaction/export', [MailTransactionController::class, 'export'])->name('mail-transaction.export');
        Route::post('mail-transaction/print', [MailTransactionController::class, 'print'])->name('mail-transaction.print');
        Route::get('search', [SearchController::class, 'index'])->name('mails.search');
        Route::get('sort', [SearchMailController::class, 'index'])->name('mails.sort');
        Route::get('select', [SearchMailController::class, 'date'])->name('mail-select-date');
        Route::get('InProgress', [MailReceivedController::class, 'inprogress'])->name('mails.inprogress');
        Route::get('approve', [MailReceivedController::class, 'approve'])->name('mails.approve');
        Route::get('feedback', [SearchMailFeedbackController::class, 'index'])->name('mails.feedback');
        Route::get('/mail-attachment/{id}/preview', [MailAttachmentController::class, 'preview'])->name('mail-attachment.preview');

        Route::get('chart', [SearchMailController::class, 'chart'])->name('mails.chart');
    });


    Route::get('/api/dollies', [DollyController::class, 'apiList']);
    Route::post('/api/dollies', [DollyController::class, 'apiCreate']);

    Route::get('/api/dollies/{dolly}/mail-transactions', [DollyMailTransactionController::class, 'apiList']);
    Route::post('/api/dolly-mail-transactions', [DollyMailTransactionController::class, 'apiStore']);
    Route::delete('/api/dolly-mail-transactions/{dolly}/{mailTransaction}', [DollyMailTransactionController::class, 'apiDestroy']);
    Route::delete('/api/dollies/{dolly}/empty-mail-transactions', [DollyMailTransactionController::class, 'apiEmptyDolly']);

    Route::get('/dollies/{dolly}/mail-transactions/process', [DollyMailTransactionController::class, 'process'])
        ->name('dollies.mail-transactions.process');


    Route::prefix('communications')->group(function () {
        Route::get('/', [CommunicationController::class, 'index']);
        Route::get('print', [CommunicationController::class, 'print'])->name('communications.print');
        Route::post('add-to-cart', [CommunicationController::class, 'addToCart'])->name('communications.addToCart');
        Route::get('export', [CommunicationController::class, 'export'])->name('communications.export');
        Route::get('reservations/sort', [SearchReservationController::class, 'index'])->name('reservations-sort');
        Route::get('reservations/select', [SearchReservationController::class, 'date'])->name('reservations-select-date');
        Route::post('reservations/approved', [ReservationController::class, 'approved'])->name('reservations-approved');
        Route::resource('transactions', CommunicationController::class);
        Route::resource('transactions.records', CommunicationRecordController::class);
        Route::resource('reservations', ReservationController::class);
        Route::resource('reservations.records', ReservationRecordController::class);
        Route::get('transactions/return', [CommunicationController::class, 'returnEffective'])->name('return-effective');
        Route::get('transactions/cancel', [CommunicationController::class, 'returnCancel'])->name('return-cancel');
        Route::get('transmission', [CommunicationController::class, 'transmission'])->name('record-transmission');
        Route::get('transactions/record/return', [CommunicationRecordController::class, 'returnEffective'])->name('record-return-effective');
        Route::get('transactions/record/cancel', [CommunicationRecordController::class, 'returnCancel'])->name('record-return-cancel');
        Route::get('sort', [SearchCommunicationController::class, 'index'])->name('communications-sort');
        Route::get('select', [SearchCommunicationController::class, 'date'])->name('communications-select-date');
        Route::get('/advanced', [SearchCommunicationController::class, 'form'])->name('communications.advanced.form');
        Route::post('/advanced', [SearchCommunicationController::class, 'advanced'])->name('search.communications.advanced');

    });


    Route::prefix('repositories')->group(function () {
        Route::post('/slips/store', [SlipController::class, 'storetransfert'])->name('slips.storetransfert');
        Route::get('/', [RecordController::class, 'index']);
        Route::get('shelve', [SearchRecordController::class, 'selectShelve'])->name('record-select-shelve');
        Route::post('dolly/create-with-records', [DollyController::class, 'createWithRecords'])->name('dolly.createWithRecords');
        Route::get('records/exportButton', [RecordController::class, 'exportButton'])->name('records.exportButton');
        Route::post('records/print', [RecordController::class, 'printRecords'])->name('records.print');
        Route::post('records/export', [RecordController::class, 'export'])->name('records.export');
        Route::get('records/export', [RecordController::class, 'exportForm'])->name('records.export.form');
        Route::post('records/container/insert', [RecordContainerController::class, 'store'])->name('record-container-insert');
        Route::post('records/container/remove', [RecordContainerController::class, 'destroy'])->name('record-container-remove');
        Route::get('records/import', [RecordController::class, 'importForm'])->name('records.import.form');
        Route::post('records/import', [RecordController::class, 'import'])->name('records.import');
        Route::resource('records', RecordController::class);
        Route::get('records/create/full', [RecordController::class, 'createFull'])->name('records.create.full');
        Route::resource('records.attachments', RecordAttachmentController::class);
        Route::get('search', [RecordController::class, 'search'])->name('records.search');
        Route::resource('authors', RecordAuthorController::class)->names('record-author');
        Route::get('authors/list', [RecordAuthorController::class, 'list'])->name('record-author.list');
        Route::resource('records.child', RecordChildController::class)->names('record-child');
        Route::get('recordtotransfer', [lifeCycleController::class, 'recordToTransfer'])->name('records.totransfer');
        Route::get('recordtosort', [lifeCycleController::class, 'recordToSort'])->name('records.tosort');
        Route::get('recordtoeliminate', [lifeCycleController::class, 'recordToEliminate'])->name('records.toeliminate');
        Route::get('recordtokeep', [lifeCycleController::class, 'recordToKeep'])->name('records.tokeep');
        Route::get('recordtoretain', [lifeCycleController::class, 'recordToRetain'])->name('records.toretain');
        Route::get('recordtostore', [lifeCycleController::class, 'recordToStore'])->name('records.tostore');
        Route::post('advanced', [SearchRecordController::class, 'advanced'])->name('records.advanced');
        Route::get('advanced/form', [SearchRecordController::class, 'form'])->name('records.advanced.form');
        Route::get('search', [SearchController::class, 'index'])->name('records.search');
        Route::get('sort', [SearchRecordController::class, 'sort'])->name('records.sort');
        Route::get('select', [SearchRecordController::class, 'date'])->name('record-select-date');
        Route::get('word', [SearchRecordController::class, 'selectWord'])->name('record-select-word');
        Route::get('activity', [SearchRecordController::class, 'selectActivity'])->name('record-select-activity');
        Route::get('building', [SearchRecordController::class, 'selectBuilding'])->name('record-select-building');
        Route::get('last', [SearchRecordController::class, 'selectLast'])->name('record-select-last');
        Route::get('floor', [SearchRecordController::class, 'selectFloor'])->name('record-select-floor');
        Route::get('container', [SearchRecordController::class, 'selectContainer'])->name('record-select-container');
        Route::get('room', [SearchRecordController::class, 'selectRoom'])->name('record-select-room');
    });


    Route::prefix('transferrings')->group(function () {
        Route::get('/advanced', [SearchSlipController::class, 'form'])->name('slips.advanced.form');
        Route::post('/advanced', [SearchSlipController::class, 'advanced'])->name('search.slips.advanced');
        Route::get('/', [SlipController::class, 'index']);
        Route::get('slips/export', [SlipController::class, 'exportForm'])->name('slips.export.form');
        Route::post('slips/export', [SlipController::class, 'export'])->name('slips.export');
        Route::get('slips/import', [SlipController::class, 'importForm'])->name('slips.import.form');
        Route::post('slips/import/{format}', [SlipController::class, 'import'])->name('slips.import');
        Route::get('search', [SearchController::class, 'index'])->name('transferrings.search');
        Route::get('slips/reception', [SlipController::class, 'reception'])->name('slips.reception');
        Route::get('slips/approve', [SlipController::class, 'approve'])->name('slips.approve');
        Route::get('slips/integrate', [SlipController::class, 'integrate'])->name('slips.integrate');
        Route::resource('slips', SlipController::class);
        Route::resource('slips.records', SlipRecordController::class);
        Route::resource('slips.records.containers', SlipRecordContainerController::class);
        Route::resource('containers', SlipContainerController::class)->names('slips.containers');
        Route::get('slip/sort', [SearchSlipController::class, 'index'])->name('slips-sort');
        Route::get('/slips/{slip}/print', [SlipController::class, 'print'])->name('slips.print');
        Route::get('slip/select', [SearchSlipController::class, 'date'])->name('slips-select-date');
        Route::get('organisation/select', [SearchSlipController::class, 'organisation'])->name('slips-select-organisation');
        Route::post('slipRecordAttachment/upload', [SlipRecordAttachmentController::class, 'upload'])->name('slip-record-upload');
        Route::post('slipRecordAttachment/show', [SlipRecordAttachmentController::class, 'show'])->name('slip-record-show');
        Route::delete('slips/{slip}/records/{record}/attachments/{id}', [SlipRecordAttachmentController::class, 'delete'])
            ->name('slipRecordAttachment.delete');

    });

    Route::prefix('deposits')->group(function () {
        Route::get('/', [BuildingController::class, 'index']);
        Route::resource('buildings', BuildingController::class);
        Route::resource('buildings.floors', FloorController::class)->names('floors');
        Route::resource('rooms', RoomController::class);
        Route::resource('shelves', ShelfController::class);
        Route::resource('containers', ContainerController::class);
        Route::resource('trolleys', BuildingController::class);
    });


    Route::prefix('settings')->group(function () {
        Route::get('activities/export/excel', [ActivityController::class, 'exportExcel'])->name('activities.export.excel');
        Route::get('activities/export/pdf', [ActivityController::class, 'exportPdf'])->name('activities.export.pdf');
        Route::get('organisations/export/excel', [OrganisationController::class, 'exportExcel'])->name('organisations.export.excel');
        Route::get('organisations/export/pdf', [OrganisationController::class, 'exportPdf'])->name('organisations.export.pdf');
        Route::get('users', [UserController::class, 'index'] );
        Route::resource('user-organisation-role', UserOrganisationRoleController::class);
        Route::resource('user-roles', UserRoleController::class);
        Route::resource('roles', RoleController::class);
        Route::resource('users', UserController::class);
        Route::resource('role_permissions', RolePermissionController::class);
        Route::resource('mail-typology', MailTypologyController::class);
        Route::resource('mail-priority', MailPriorityController::class);
        Route::resource('container-status', ContainerStatusController::class);
        Route::resource('container-property', ContainerPropertyController::class);
        Route::resource('sorts', SortController::class);
        Route::resource('term-categories', TermCategoryController::class);
        Route::resource('term-equivalent-types', TermEquivalentTypeController::class);
        Route::resource('term-types', TermTypeController::class);
        Route::resource('languages', LanguageController::class);
        Route::resource('record-supports', RecordSupportController::class);
        Route::resource('communication-status', CommunicationStatusController::class);
        Route::resource('reservation-status', ReservationStatusController::class);
        Route::resource('record-statuses', RecordStatusController::class);
        Route::resource('transferring-status', SlipStatusController::class);
        Route::resource('mail-action', MailActionController::class);
        Route::resource('taskstatus', TaskStatusController::class);
        Route::resource('tasktype', TaskTypeController::class);
        Route::resource('logs', LogController::class)->only(['index', 'show']);
        Route::resource('backups', BackupController::class);
        Route::resource('backups.files', BackupFileController::class);
        Route::resource('backups.plannings', BackupPlanningController::class);
    });



    Route::prefix('dollies')->group(function () {
        Route::get('/', [DollyController::class, 'index']);
        Route::post('create-with-communications', [DollyController::class, 'createWithCommunications'])->name('dolly.createWithCommunications');
        Route::resource('dolly', DollyController::class)->names('dolly');
        Route::get('action', [DollyActionController::class, 'index'])->name('dollies.action');
        Route::get('sort', [SearchdollyController::class, 'index'])->name('dollies-sort');
        Route::delete('{dolly}/remove-record/{record}', [DollyController::class, 'removeRecord'])->name('dolly.remove-record');
        Route::delete('{dolly}/remove-mail/{mail}', [DollyController::class, 'removeMail'])->name('dolly.remove-mail');
        Route::post('{dolly}/add-record', [DollyController::class, 'addRecord'])->name('dolly.add-record');
        Route::post('{dolly}/add-mail', [DollyController::class, 'addMail'])->name('dolly.add-mail');
        Route::post('{dolly}/add-communication', [DollyController::class, 'addCommunication'])->name('dolly.add-communication');
        Route::post('{dolly}/add-room', [DollyController::class, 'addRoom'])->name('dolly.add-room');
        Route::post('{dolly}/add-container', [DollyController::class, 'addContainer'])->name('dolly.add-container');
        Route::post('{dolly}/add-shelve', [DollyController::class, 'addShelve'])->name('dolly.add-shelve');
        Route::post('{dolly}/add-slip-record', [DollyController::class, 'addSlipRecord'])->name('dolly.add-slip-record');
    });



    Route::prefix('tools')->group(function () {
        Route::get('/', [ActivityController::class , 'index' ] );
        Route::resource('activities', ActivityController::class)->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        Route::resource('activities.retentions', retentionActivityController::class);
        Route::resource('retentions', RetentionController::class);
        Route::resource('retentions.exigences', RetentionLawArticleController::class);
        Route::resource('laws', LawController::class);
        Route::resource('laws.Articles', LawArticleController::class);
        Route::resource('communicabilities', CommunicabilityController::class);
        Route::resource('activities.communicabilities', activityCommunicabilityController::class);
        Route::resource('thesaurus', ContainerStatusController::class);
        Route::resource('organisations', OrganisationController::class);
        Route::resource('organisations.rooms', OrganisationRoomController::class);
        Route::resource('organisations.activities', OrganisationActivityController::class);
        Route::resource('access', ContainerStatusController::class);
        Route::resource('terms', TermController::class);
        Route::get('barcode', [BarcodeController::class,'create'])->name('barcode.create');
        Route::post('/barcodes/preview', [BarcodeController::class, 'preview'])->name('barcode.preview');
        Route::get('/barcodes', [BarcodeController::class, 'index'])->name('barcode.index');
        Route::post('/barcodes/generate', [BarcodeController::class, 'generate'])->name('barcode.generate');
        Route::resource('terms.term-related', TermRelatedController::class)->names('term-related');
        Route::resource('terms.term-equivalents', TermEquivalentController::class)->names('term-equivalents');
        Route::resource('terms.term-translations', TermTranslationController::class)->names('term-translations');
    });



    Route::prefix('dashboard')->group(function () {
        Route::get('/', [ReportController::class, 'dashboard'])->name('report.dashboard');
        Route::get('mails', [ReportController::class, 'statisticsMails'])->name('report.statistics.mails');
        Route::get('repositories', [ReportController::class, 'statisticsRepositories'])->name('report.statistics.repositories');
        Route::get('communications', [ReportController::class, 'statisticsCommunications'])->name('report.statistics.communications');
        Route::get('transferrings', [ReportController::class, 'statisticsTransferrings'])->name('report.statistics.transferrings');
        Route::get('deposits', [ReportController::class, 'statisticsDeposits'])->name('report.statistics.deposits');
        Route::get('tools', [ReportController::class, 'statisticsTools'])->name('report.statistics.tools');
        Route::get('dollies', [ReportController::class, 'statisticsDollies'])->name('report.statistics.dollies');
    });

    Route::get('language/{locale}', [LanguageController::class, 'switch'])->name('language.switch');

});




