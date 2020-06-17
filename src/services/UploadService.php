<?php


namespace BackOffice\services;


use Aws\S3\S3Client;

class UploadService
{
    /**
     * @var S3Client
     */
    private S3Client $s3Client;

    function __construct()
    {
        $credentials = array('key' => getenv('AWS_KEY'), 'secret' => getenv('AWS_SECRET'));
        $this->s3Client = new S3Client([
            'version' => 'latest',
            'region' => 'eu-west-2',
            'endpoint' => "https://osu.eu-west-2.outscale.com/",
            'credentials' => $credentials,
        ]);
    }

    function run($name, $file)
    {
        $this->s3Client->upload('backoffice', $name, $file);
        $this->s3Client->putObjectAcl(['ACL' => "public-read", 'Bucket' => 'backoffice', 'Key' => $name]);
        $cmd = $this->s3Client->getCommand('GetObject', [
            'Bucket' => 'backoffice',
            'Key' => $name
        ]);
        return (string)$this->s3Client->createPresignedRequest($cmd, -1)->getUri();
    }

}