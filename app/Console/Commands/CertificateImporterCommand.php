<?php

namespace App\Console\Commands;

use App\Enumerations\CertificateRequestStatus;
use App\Models\Certificate;
use App\Models\Company;
use App\Models\PublicService;
use App\Models\User;
use App\ValueObjects\CertificateVO;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CertificateImporterCommand extends Command
{
    protected $signature = 'certificate:importer {file} {service} {company} {user}';

    protected $description = 'Import a single certificate into database. You mast pass the IDs of service, company and user';

    public function handle(): void
    {
        $file = $this->argument('file');
        $privateKeyFileName = $file.config('importer.importer_x509_key_suffix').'.pem';
        $publicCertFileName = $file.config('importer.importer_x509_cert_suffix').'.pem';
        $serviceId = $this->argument('service');
        $companyId = $this->argument('company');
        $userId = $this->argument('user');

        if (! Storage::disk('importer')->exists($privateKeyFileName) || ! Storage::disk('importer')->exists($publicCertFileName)) {
            $this->error("The keypair does not exist. Both {$privateKeyFileName} and {$publicCertFileName} must be present");

            return;
        }

        $privateKey = Storage::disk('importer')->get($privateKeyFileName);
        $publicCert = Storage::disk('importer')->get($publicCertFileName);
        $certificateInfo = CertificateVO::fromPemCertificate($publicCert);

        try {
            $service = PublicService::findOrFail($serviceId);
            $company = Company::findOrFail($companyId);
            $user = User::findOrFail($userId);
        } catch (Exception $exception) {
            $this->error($exception->getMessage());

            return;
        }

        if ($user->company->id !== $company->id) {
            $this->error("The user {$user->name} does not belong to the company {$company->name}");

            return;
        }
        Certificate::create([
            'user_id' => $user->id,
            'public_service_id' => $service->id,
            'company_id' => $company->id,
            'common_name' => $certificateInfo->commonName,
            'valid_from' => $certificateInfo->validFrom,
            'valid_to' => $certificateInfo->validTo,
            'private_key' => $privateKey,
            'public_cert' => $publicCert,
            'status' => CertificateRequestStatus::ISSUED,
        ])->save();
    }
}
