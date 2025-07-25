<?php

namespace App\Http\Controllers;
use App\Exports\RecordsExport;
use App\Imports\RecordsImport;
use App\Models\RecordAttachment;
use App\Models\SlipStatus;
use App\Models\Attachment;
use App\Models\Dolly;
use App\Models\Organisation;
use App\Models\Record;
use App\Models\RecordSupport;
use App\Models\RecordStatus;
use App\Models\Container;
use App\Models\Activity;
use App\Models\Slip;
use App\Models\ThesaurusConcept;
use App\Models\User;
use App\Models\Accession;
use App\Models\Author;
use App\Models\AuthorType;
use App\Models\RecordLevel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use ZipArchive;


class RecordController extends Controller
{
    public function search(Request $request)
    {

        $query = $request->input('query');
        $results = Record::search($query)->paginate(10);
        return view('records.search', compact('results', 'query'));
    }

    public function index()
    {
        $this->authorize('viewAny', Record::class);

        if (Gate::allows('viewAny', Record::class)) {
            // L'utilisateur a la permission de voir tous les records
            $records = Record::with([
                'level', 'status', 'support', 'activity', 'containers', 'authors', 'thesaurusConcepts'
            ])->paginate(10);
        } else {
            // L'utilisateur ne peut voir que les records associés aux activités de son organisation actuelle
            $currentOrganisationId = auth::user()->current_organisation_id;

            $records = Record::with([
                'level', 'status', 'support', 'activity', 'containers', 'authors', 'thesaurusConcepts'
            ])
                ->whereHas('activity', function($query) use ($currentOrganisationId) {
                    $query->whereHas('organisations', function($q) use ($currentOrganisationId) {
                        $q->where('organisations.id', $currentOrganisationId);
                    });
                })
                ->paginate(10);
        }

        $slipStatuses = SlipStatus::all();
        $statuses = RecordStatus::all();
        $terms = [];
        $users = User::select('id', 'name')->get();
        $organisations = Organisation::select('id', 'name')->get();

        return view('records.index', compact(
            'records',
            'statuses',
            'slipStatuses',
            'terms',
            'users',
            'organisations'
        ));
    }

    public function create()
    {
        $this->authorize('create', Record::class);

        $statuses = RecordStatus::all();
        $supports = RecordSupport::all();
        $activities = Activity::all();
        $parents = Record::all();
        $containers = Container::all();
        $users = User::all();
        $levels = RecordLevel::all();
        $records = Record::all();
        $authors = Author::with('type')->get();
        $terms = [];
        $authorTypes = AuthorType::all();
        $parents = Author::all();
        return view('records.create', compact('authorTypes', 'parents','records','authors','levels','statuses', 'supports', 'activities', 'parents', 'containers', 'users'));
    }


    public function createFull()
    {
        $this->authorize('create', Record::class);

        $statuses = RecordStatus::all();
        $supports = RecordSupport::all();
        $activities = Activity::all();
        $parents = Record::all();
        $containers = Container::all();
        $users = User::all();
        $levels = RecordLevel::all();
        $records = Record::all();
        $authors = Author::with('type')->get();
        $terms = [];
        $authorTypes = AuthorType::all();
        $parents = Author::all();
        return view('records.createFull', compact('authorTypes', 'parents','records','authors','levels','statuses', 'supports', 'activities', 'parents', 'containers', 'users'));
    }

    public function store(Request $request)
    {
        $this->authorize('create', Record::class);

        $dateFormat = $this->getDateFormat($request->date_start, $request->date_end);
        if (strlen($dateFormat) > 1) {
            return back()->withErrors(['date_format' => 'The date format must not be greater than 1 character.'])->withInput();
        }

        $request->merge([
            'date_format' => $dateFormat,
            'user_id' => Auth::id(),
            'organisation_id' => Auth::user()->current_organisation_id,
        ]);

        $validatedData = $request->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string',
            'date_format' => 'required|string|max:1',
            'date_start' => 'nullable|string|max:10',
            'date_end' => 'nullable|string|max:10',
            'date_exact' => 'nullable|date',
            'level_id' => 'required|integer|exists:record_levels,id',
            'width' => 'nullable|numeric|between:0,99999999.99',
            'width_description' => 'nullable|string|max:100',
            'biographical_history' => 'nullable|string',
            'archival_history' => 'nullable|string',
            'acquisition_source' => 'nullable|string',
            'content' => 'nullable|string',
            'appraisal' => 'nullable|string',
            'accrual' => 'nullable|string',
            'arrangement' => 'nullable|string',
            'access_conditions' => 'nullable|string|max:50',
            'reproduction_conditions' => 'nullable|string|max:50',
            'language_material' => 'nullable|string|max:50',
            'characteristic' => 'nullable|string|max:100',
            'finding_aids' => 'nullable|string|max:100',
            'location_original' => 'nullable|string|max:100',
            'location_copy' => 'nullable|string|max:100',
            'related_unit' => 'nullable|string|max:100',
            'publication_note' => 'nullable|string',
            'note' => 'nullable|string',
            'archivist_note' => 'nullable|string',
            'rule_convention' => 'nullable|string|max:100',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
            'status_id' => 'required|integer|exists:record_statuses,id',
            'support_id' => 'required|integer|exists:record_supports,id',
            'activity_id' => 'required|integer|exists:activities,id',
            'parent_id' => 'nullable|integer|exists:records,id',
            'container_id' => 'nullable|integer|exists:containers,id',
            'accession_id' => 'nullable|integer|exists:accessions,id',
            'user_id' => 'required|integer|exists:users,id',
            'author_ids' => 'required|array',
            'term_ids' => 'required|array',
        ]);

        $record = Record::create($validatedData);

        $term_ids = $request->input('term_ids');
        $author_ids = $request->input('author_ids');
        $term_ids = explode(',', $term_ids[0]);

        $author_ids = explode(',', $author_ids[0]);

        $term_ids = array_map('intval', $term_ids);
        $author_ids = array_map('intval', $author_ids);


        foreach ($author_ids as $author_id) {
            $record->authors()->attach($author_id);
        }

        foreach ($term_ids as $term_id) {
            $record->thesaurusConcepts()->attach($term_id, [
                'weight' => 1.0,  // Poids par défaut
                'context' => 'manuel',  // Contexte de l'ajout (manuel par l'utilisateur)
                'extraction_note' => null  // Pas de note d'extraction pour un ajout manuel
            ]);
        }

        return redirect()->route('records.index')->with('success', 'Record created successfully.');
    }

    private function getDateFormat($dateStart, $dateEnd)
    {
        $start = new \DateTime($dateStart);
        $end = new \DateTime($dateEnd);

        if ($start->format('Y') !== $end->format('Y')) {
            return 'Y';
        } elseif ($start->format('m') !== $end->format('m')) {
            return 'M';
        } elseif ($start->format('d') !== $end->format('d')) {
            return 'D';
        }
        return 'D';
    }

    public function show(Record $record)
    {
        $this->authorize('view', $record);

        $record->load('children');
        return view('records.show', compact('record'));
    }

    public function showFull(Record $record)
    {
        $this->authorize('view', $record);

        // Charger toutes les relations pour la vue détaillée
        $record->load([
            'children',
            'parent',
            'level',
            'status',
            'support',
            'activity',
            'authors',
            'containers',
            'recordContainers.container',
            'thesaurusConcepts',
            'attachments',
            'user',
            'organisation'
        ]);

        return view('records.showFull', compact('record'));
    }

    public function edit(Record $record)
    {
        $this->authorize('update', $record);

        $authors = Author::with('authorType')->get();
        $statuses = RecordStatus::all();
        $supports = RecordSupport::all();
        $activities = Activity::all();
        $parents = Record::all();
        $containers = Container::all();
        $users = User::all();
        $levels = RecordLevel::all();
        $terms = ThesaurusConcept::all(); // Chargement des concepts du thésaurus

        $author_ids = $record->authors->pluck('id')->toArray();
        $term_ids = $record->thesaurusConcepts->pluck('id')->toArray();

        return view('records.edit', compact('levels', 'record', 'statuses', 'supports', 'activities', 'parents', 'containers', 'users', 'authors', 'author_ids', 'term_ids'));
    }


    public function update(Request $request, Record $record)
    {
        $this->authorize('update', $record);

        $request->merge(['date_format' => $request->input('date_format', 'Y')]);
        $request->merge(['user_id' => Auth::id()]);
        $validatedData = $request->validate([
            'code' => 'required|string|max:10',
            'name' => 'required|string',
            'date_format' => 'required|string|max:1',
            'date_start' => 'nullable|string|max:10',
            'date_end' => 'nullable|string|max:10',
            'date_exact' => 'nullable|date',
            'level_id' => 'required|integer|exists:record_levels,id',
            'width' => 'nullable|numeric|between:0,99999999.99',
            'width_description' => 'nullable|string|max:100',
            'biographical_history' => 'nullable|string',
            'archival_history' => 'nullable|string',
            'acquisition_source' => 'nullable|string',
            'content' => 'nullable|string',
            'appraisal' => 'nullable|string',
            'accrual' => 'nullable|string',
            'arrangement' => 'nullable|string',
            'access_conditions' => 'nullable|string|max:50',
            'reproduction_conditions' => 'nullable|string|max:50',
            'language_material' => 'nullable|string|max:50',
            'characteristic' => 'nullable|string|max:100',
            'finding_aids' => 'nullable|string|max:100',
            'location_original' => 'nullable|string|max:100',
            'location_copy' => 'nullable|string|max:100',
            'related_unit' => 'nullable|string|max:100',
            'publication_note' => 'nullable|string',
            'note' => 'nullable|string',
            'archivist_note' => 'nullable|string',
            'rule_convention' => 'nullable|string|max:100',
            'created_at' => 'nullable|date',
            'updated_at' => 'nullable|date',
            'status_id' => 'required|integer|exists:record_statuses,id',
            'support_id' => 'required|integer|exists:record_supports,id',
            'activity_id' => 'required|integer|exists:activities,id',
            'parent_id' => 'nullable|integer|exists:records,id',
            'container_id' => 'nullable|integer|exists:containers,id',
            'accession_id' => 'nullable|integer|exists:accessions,id',
            'user_id' => 'required|integer|exists:users,id',
            'author_ids' => 'required|array',
            'term_ids' => 'required|array',
        ]);

        // Mettez à jour l'enregistrement
        $record->update($validatedData);
        // Supprimez les clés author_ids et term_ids du tableau $validatedData
        $term_ids = $request->input('term_ids');
        $author_ids = $request->input('author_ids');
        $term_ids = explode(',', $term_ids[0]);

        $author_ids = explode(',', $author_ids[0]);
        // Supprimez les valeurs vides du tableau
        //        $term_ids = array_filter($term_ids);
        //        $author_ids = array_filter($author_ids);

        // Convertissez les valeurs en entiers

        $term_ids = array_map('intval', $term_ids);
        $author_ids = array_map('intval', $author_ids);

        // Mettez à jour les relations entre les auteurs et l'enregistrement
        $record->authors()->sync($author_ids);

        // Mettez à jour les relations entre les concepts du thésaurus et l'enregistrement
        if (!empty($term_ids)) {
            $conceptData = [];
            foreach ($term_ids as $conceptId) {
                $conceptData[$conceptId] = ['weight' => 1.0]; // Poids par défaut à 1.0
            }
            $record->thesaurusConcepts()->sync($conceptData);
        } else {
            $record->thesaurusConcepts()->detach();
        }

        return redirect()->route('records.index')->with('success', 'Record updated successfully.');
    }


    public function destroy(Record $record)
    {
        $this->authorize('delete', $record);

        $record->delete();

        return redirect()->route('records.index')->with('success', 'Record deleted successfully.');
    }


    // ici c\'est pour l'import export
    public function exportButton(Request $request)
    {
        // Vérifier les permissions d'export pour les records
        $this->authorize('export', Record::class);

        $recordIds = explode(',', $request->query('records'));
        $format = $request->query('format', 'excel');
        $records = Record::whereIn('id', $recordIds)->get();

        $slips = "";
        try {
            switch ($format) {
                case 'excel':
                    return Excel::download(new RecordsExport($records), 'records_export.xlsx');
                case 'ead':
                    $xml = $this->generateEAD($records);
                    return response($xml)
                        ->header('Content-Type', 'application/xml')
                        ->header('Content-Disposition', 'attachment; filename="records_export.xml"');
                case 'seda':
                    return $this->exportSEDA($records,$slips);
                default:
                    return response()->json(['error' => 'Format d\'exportation non valide.'], 400);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'exportation: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue lors de l\'exportation.'], 500);
        }
    }


    public function export(Request $request)
    {
        $this->authorize('export', Record::class);

        $dollyId = $request->input('dolly_id');
        $format = $request->input('format');

        if ($dollyId) {
            $dolly = Dolly::findOrFail($dollyId);
            $records = $dolly->records;
            $slips = $dolly->slips;
        } else {
            $records = Record::all();
            $slips = Slip::all();
        }

        switch ($format) {
            case 'excel':
                return Excel::download(new RecordsExport($records), 'records.xlsx');
            case 'ead':
                $xml = $this->generateEAD($records);
                return response($xml)
                    ->header('Content-Type', 'application/xml')
                    ->header('Content-Disposition', 'attachment; filename="records.xml"');
            case 'seda':
                return $this->exportSEDA($records, $slips);
            default:
                return redirect()->back()->with('error', 'Invalid export format');
        }
    }


    public function importForm()
    {
        $this->authorize('import', Record::class);

        return view('records.import');
    }


    public function exportForm()
    {
        $this->authorize('export', Record::class);

        $dollies = Dolly::all();
        return view('records.export', compact('dollies'));
    }

    public function import(Request $request)
    {
        $this->authorize('import', Record::class);
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xml',
            'format' => 'required|in:excel,ead,seda',
        ]);

        $file = $request->file('file');
        $format = $request->input('format');

        // Créer un nouveau Dolly
        $dolly = Dolly::create([
            'name' => 'Import ' . now()->format('Y-m-d H:i:s'),
            'description' => 'Imported data',
            'type_id' => 1, // Assurez-vous d'avoir un type par défaut
        ]);

        try {
            switch ($format) {
                case 'excel':
                    Excel::import(new RecordsImport($dolly), $file);
                    break;
                case 'ead':
                    $this->importEAD($file, $dolly);
                    break;
                case 'seda':
                    $this->importSEDA($file, $dolly);
                    break;
            }
            return redirect()->route('records.index')->with('success', 'Records imported successfully and attached to new Dolly.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error importing records: ' . $e->getMessage());
        }
    }

    private function generateEAD($records)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
    <ead xmlns="urn:isbn:1-931666-22-9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="urn:isbn:1-931666-22-9">
    </ead>');

        $eadheader = $xml->addChild('eadheader');
        $eadheader->addChild('eadid', 'YOUR_UNIQUE_ID');
        $filedesc = $eadheader->addChild('filedesc');
        $filedesc->addChild('titlestmt')->addChild('titleproper', 'Your Archive Title');

        $archdesc = $xml->addChild('archdesc');
        $archdesc->addAttribute('level', 'collection');
        $did = $archdesc->addChild('did');
        $did->addChild('unittitle', 'Your Collection Title');

        foreach ($records as $record) {
            $c = $archdesc->addChild('c');
            $c->addAttribute('level', $record->level->name ?? 'item');
            $c_did = $c->addChild('did');
            $c_did->addChild('unittitle', $record->name);
            $c_did->addChild('unitdate', $record->date_start)->addAttribute('normal', $record->date_start);
            $c_did->addChild('physdesc')->addChild('extent', $record->width_description);

            if ($record->content) {
                $c->addChild('scopecontent')->addChild('p', $record->content);
            }
        }

        // Format the XML with indentation
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        return $dom->saveXML();
    }

    private function exportSEDA($records, $slips)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?>
        <ArchiveTransfer xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="fr:gouv:culture:archivesdefrance:seda:v2.1 seda-2.1-main.xsd" xmlns="fr:gouv:culture:archivesdefrance:seda:v2.1">
        </ArchiveTransfer>');

        $xml->addChild('Comment', 'Archive Transfer');
        $xml->addChild('Date', date('Y-m-d'));

        $archive = $xml->addChild('Archive');

        foreach ($records as $record) {
            $archiveObject = $archive->addChild('ArchiveObject');
            $archiveObject->addChild('Name', $record->name);
            $archiveObject->addChild('Description', $record->content);

            $document = $archiveObject->addChild('Document');
            $document->addChild('Identification', $record->code);
            $document->addChild('Type', $record->level->name ?? 'item');

            foreach ($record->attachments as $attachment) {
                $attachmentNode = $document->addChild('Attachment');
                $attachmentNode->addChild('FileName', $attachment->name . '.pdf');
                $attachmentNode->addChild('Size', $attachment->size);
                $attachmentNode->addChild('Path', 'attachments/' . $attachment->name . '.pdf');
                $attachmentNode->addChild('Crypt', $attachment->crypt);
            }
        }

        // Format the XML with indentation
        $dom = new \DOMDocument('1.0');
        $dom->preserveWhiteSpace = false;
        $dom->formatOutput = true;
        $dom->loadXML($xml->asXML());

        $formattedXml = $dom->saveXML();

        $zipFileName = 'records_seda_export_' . time() . '.zip';
        $zip = new ZipArchive();

        if ($zip->open(storage_path('app/public/' . $zipFileName), ZipArchive::CREATE) === TRUE) {
            $zip->addFromString('records.xml', $formattedXml);

            foreach ($records as $record) {
                foreach ($record->attachments as $attachment) {
                    $filePath = storage_path('app/' . $attachment->path);
                    if (file_exists($filePath)) {
                        $zip->addFile($filePath, 'attachments/' . $attachment->name . '.pdf');
                    }
                }
            }

            $zip->close();
        }

        return response()->download(storage_path('app/public/' . $zipFileName))->deleteFileAfterSend(true);
    }

    private function generateSEDA($records)
    {
        $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><ArchiveTransfer xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="fr:gouv:culture:archivesdefrance:seda:v2.1 seda-2.1-main.xsd" xmlns="fr:gouv:culture:archivesdefrance:seda:v2.1"></ArchiveTransfer>');

        $xml->addChild('Comment', 'Archive Transfer');
        $xml->addChild('Date', date('Y-m-d'));

        $archive = $xml->addChild('Archive');

        foreach ($records as $record) {
            $archiveObject = $archive->addChild('ArchiveObject');
            $archiveObject->addChild('Name', $record->name);
            $archiveObject->addChild('Description', $record->content);

            $document = $archiveObject->addChild('Document');
            $document->addChild('Identification', $record->code);
            $document->addChild('Type', $record->level->name ?? 'item');

            foreach ($record->attachments as $attachment) {
                $attachmentNode = $document->addChild('Attachment');
                $attachmentNode->addChild('FileName', $attachment->name . '.pdf');  // Added .pdf extension
                $attachmentNode->addChild('Size', $attachment->size);
                $attachmentNode->addChild('Path', 'attachments/' . $attachment->name . '.pdf');  // Added .pdf extension
                $attachmentNode->addChild('Crypt', $attachment->crypt);
            }
        }

        return $xml->asXML();
    }

    private function importEAD($file, $dolly)
    {
        $xml = simplexml_load_file($file);
        $xml->registerXPathNamespace('ead', 'urn:isbn:1-931666-22-9');

        $records = $xml->xpath('//ead:c');

        foreach ($records as $record) {
            $data = [
                'name' => (string)$record->did->unittitle,
                'date_start' => (string)$record->did->unitdate,
                'content' => (string)$record->scopecontent->p,
                // Map other fields as needed
            ];

            $newRecord = Record::create($data);
            $dolly->records()->attach($newRecord->id);
        }
    }

    private function importSEDA($file, $dolly)
    {
        $zip = new ZipArchive;
        $extractPath = storage_path('app/temp_import');

        if ($zip->open($file) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();

            $xmlFile = $extractPath . '/records.xml';
            $xml = simplexml_load_file($xmlFile);
            $xml->registerXPathNamespace('seda', 'fr:gouv:culture:archivesdefrance:seda:v2.1');

            $records = $xml->xpath('//seda:ArchiveObject');

            foreach ($records as $record) {
                $data = [
                    'name' => (string)$record->Name,
                    'content' => (string)$record->Description,
                    'code' => (string)$record->Document->Identification,
                    // Map other fields as needed
                ];

                $newRecord = Record::create($data);
                $dolly->records()->attach($newRecord->id);
                // Import attachments
                $attachments = $record->xpath('Document/Attachment');
                foreach ($attachments as $attachment) {
                    $fileName = (string)$attachment->FileName;
                    $filePath = $extractPath . '/attachments/' . $fileName;

                    if (file_exists($filePath)) {
                        $newAttachment = new RecordAttachment([
                            'name' => $fileName,
                            'path' => 'attachments/' . $fileName,
                            'size' => (int)$attachment->Size,
                            'crypt' => (string)$attachment->Crypt,
                            'creator_id' => Auth::id(), // Assuming the current user is the creator
                        ]);

                        $newRecord->attachments()->save($newAttachment);

                        // Move file to the correct storage location
                        Storage::putFileAs('public/attachments', $filePath, $fileName);
                    }
                }
            }

            // Clean up temporary files
            Storage::deleteDirectory('temp_import');
        }
    }

    public function printRecords(Request $request)
    {
        $recordIds = $request->input('records');
        $records = Record::whereIn('id', $recordIds)->get();

        $pdf = PDF::loadView('records.print', compact('records'));
        return $pdf->download('records_print.pdf');
    }

    /**
     * Get autocomplete suggestions for records
     */
    public function autocomplete(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3|max:255',
            'limit' => 'nullable|integer|min:1|max:5',
        ]);

        $query = $request->get('q');
        $limit = $request->get('limit', 5);

        // Recherche dans les records par nom et code
        $records = Record::where(function($q) use ($query) {
            $q->where('name', 'LIKE', '%' . $query . '%')
              ->orWhere('code', 'LIKE', '%' . $query . '%');
        })
        ->select('id', 'name', 'code')
        ->limit($limit)
        ->get();

        $suggestions = $records->map(function ($record) {
            return [
                'id' => $record->id,
                'label' => $record->name . ' (' . $record->code . ')',
                'name' => $record->name,
                'code' => $record->code
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $suggestions
        ]);
    }

    /**
     * Get autocomplete suggestions for thesaurus terms
     */
    public function autocompleteTerms(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:3|max:255',
            'limit' => 'nullable|integer|min:1|max:10',
        ]);

        $query = $request->get('q');
        $limit = $request->get('limit', 5);

        // Utiliser le même modèle que le ThesaurusController
        $concepts = ThesaurusConcept::with(['labels', 'scheme'])
            ->whereHas('labels', function($q) use ($query) {
                $q->where('literal_form', 'LIKE', "%{$query}%");
            })
            ->limit($limit)
            ->get();

        $results = $concepts->map(function($concept) {
            $preferredLabel = $concept->labels->where('type', 'prefLabel')->first();
            return [
                'id' => $concept->id,
                'text' => $preferredLabel ? $preferredLabel->literal_form : 'No label',
                'scheme' => $concept->scheme ? $concept->scheme->title : null
            ];
        });

        return response()->json($results);
    }

    /**
     * Analyser plusieurs documents numériques et proposer une description et indexation
     */
    public function analyzeAttachments(Request $request)
    {
        $this->authorize('create', Record::class);

        $request->validate([
            'attachment_ids' => 'required|array|min:1|max:20',
            'attachment_ids.*' => 'integer|exists:attachments,id',
            'analysis_options' => 'nullable|array',
            'record_options' => 'nullable|array',
            'indexing_options' => 'nullable|array',
            'model_name' => 'nullable|string'
        ]);

        try {
            $attachmentIds = $request->input('attachment_ids');
            $analysisOptions = $request->input('analysis_options', []);
            $recordOptions = $request->input('record_options', []);
            $indexingOptions = $request->input('indexing_options', []);
            $modelName = $request->input('model_name', config('ollama.default_model', 'llama3'));

            // URL de base du serveur MCP
            $mcpBaseUrl = config('mcp.base_url', 'http://localhost:3000');

            // Appeler l'API MCP pour l'analyse complète
            $response = Http::timeout(120)->post($mcpBaseUrl . '/api/attachments/generate-complete', [
                'attachmentIds' => $attachmentIds,
                'recordOptions' => array_merge([
                    'template' => 'detailed',
                    'includeArrangement' => true,
                    'includeAccessConditions' => true,
                    'contextualInfo' => [
                        'organisation' => Auth::user()->organisation->name ?? '',
                        'user' => Auth::user()->name
                    ]
                ], $recordOptions),
                'indexingOptions' => array_merge([
                    'maxTerms' => 15,
                    'weightingMethod' => 'combined',
                    'autoAssign' => false
                ], $indexingOptions),
                'modelName' => $modelName
            ]);

            if ($response->successful()) {
                $analysis = $response->json();

                // Récupérer les informations des attachments pour l'affichage
                $attachments = Attachment::whereIn('id', $attachmentIds)->get();

                return view('records.ai-analysis', compact('analysis', 'attachments'));
            } else {
                Log::error('Erreur API MCP:', $response->json());
                return back()->with('error', 'Erreur lors de l\'analyse des documents: ' . $response->body());
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'analyse des attachments:', [
                'error' => $e->getMessage(),
                'attachment_ids' => $request->input('attachment_ids')
            ]);

            return back()->with('error', 'Erreur lors de l\'analyse des documents: ' . $e->getMessage());
        }
    }

    /**
     * Créer un record basé sur l'analyse IA des attachments
     */
    public function createFromAiAnalysis(Request $request)
    {
        $this->authorize('create', Record::class);

        $request->validate([
            'suggested_record' => 'required|array',
            'suggested_indexing' => 'required|array',
            'attachment_ids' => 'required|array'
        ]);

        try {
            $suggestedRecord = $request->input('suggested_record');
            $suggestedIndexing = $request->input('suggested_indexing');
            $attachmentIds = $request->input('attachment_ids');

            // Mapper les données suggérées vers les champs du record
            $recordData = [
                'code' => $this->generateRecordCode(),
                'name' => $suggestedRecord['title'] ?? 'Titre généré automatiquement',
                'date_format' => 'Y',
                'date_start' => $suggestedRecord['dateStart'] ?? null,
                'date_end' => $suggestedRecord['dateEnd'] ?? null,
                'content' => $suggestedRecord['description'] ?? '',
                'scope' => $suggestedRecord['scope'] ?? '',
                'arrangement' => $suggestedRecord['arrangement'] ?? '',
                'access_conditions' => $suggestedRecord['accessConditions'] ?? '',
                'reproduction_conditions' => $suggestedRecord['reproductionConditions'] ?? '',
                'language_material' => $suggestedRecord['language'] ?? 'français',
                'note' => $suggestedRecord['notes'] ?? '',
                'user_id' => Auth::id(),
                'organisation_id' => Auth::user()->current_organisation_id ?? 1,
                'status_id' => 1, // À définir selon votre logique
                'support_id' => $this->getSupportIdFromSuggestion($suggestedRecord['suggestedSupport'] ?? 'numérique'),
                'level_id' => $this->getLevelIdFromSuggestion($suggestedRecord['suggestedLevel'] ?? 'file'),
                'activity_id' => 1, // À définir selon votre logique
            ];

            // Créer le record
            $record = Record::create($recordData);

            // Associer les termes du thésaurus si suggérés
            if (isset($suggestedIndexing['weightedTerms']) && is_array($suggestedIndexing['weightedTerms'])) {
                foreach ($suggestedIndexing['weightedTerms'] as $weightedTerm) {
                    $record->thesaurusConcepts()->attach($weightedTerm['termId'], [
                        'weight' => $weightedTerm['weight'] ?? 1.0,
                        'context' => $weightedTerm['context'] ?? 'IA',
                        'extraction_note' => 'Généré automatiquement par IA - ' . ($weightedTerm['justification'] ?? '')
                    ]);
                }
            }

            // Associer les attachments au record
            foreach ($attachmentIds as $attachmentId) {
                RecordAttachment::create([
                    'record_id' => $record->id,
                    'attachment_id' => $attachmentId,
                    'creator_id' => Auth::id()
                ]);
            }

            return redirect()->route('records.show', $record->id)
                ->with('success', 'Record créé avec succès à partir de l\'analyse IA.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création du record IA:', [
                'error' => $e->getMessage(),
                'suggested_record' => $request->input('suggested_record')
            ]);

            return back()->with('error', 'Erreur lors de la création du record: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de sélection d'attachments pour l'analyse IA
     */
    public function selectAttachmentsForAnalysis(Request $request, $record_id = null)
    {
        $this->authorize('create', Record::class);

        // Si un record_id est fourni, récupérer ce record spécifique
        $record = null;
        if ($record_id) {
            $record = Record::findOrFail($record_id);
            $this->authorize('update', $record);

            // Récupérer les attachments de ce record spécifique
            $attachments = $record->attachments()
                ->with('creator:id,name')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        } else {
            // Récupérer tous les attachments disponibles
            $attachments = Attachment::with('creator:id,name')
                ->orderBy('created_at', 'desc')
                ->paginate(20);
        }

        return view('records.select-attachments', compact('attachments', 'record'));
    }

    /**
     * Générer un code unique pour le record
     */
    private function generateRecordCode()
    {
        $prefix = 'AI_';
        $date = date('Ymd');
        $counter = Record::where('code', 'like', $prefix . $date . '%')->count() + 1;

        return $prefix . $date . '_' . str_pad($counter, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Obtenir l'ID du support basé sur la suggestion
     */
    private function getSupportIdFromSuggestion($suggestion)
    {
        $mappings = [
            'numérique' => 1,
            'papier' => 2,
            'mixte' => 3
        ];

        return $mappings[$suggestion] ?? 1;
    }

    /**
     * Obtenir l'ID du niveau basé sur la suggestion
     */
    private function getLevelIdFromSuggestion($suggestion)
    {
        $mappings = [
            'fonds' => 1,
            'series' => 2,
            'file' => 3,
            'item' => 4
        ];

        return $mappings[$suggestion] ?? 3;
    }
}
