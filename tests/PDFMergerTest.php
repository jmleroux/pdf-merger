<?php

declare(strict_types=1);

namespace Jmleroux\Tests;

use InvalidArgumentException;
use Jmleroux\PDFMerger\PDFMerger;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class PDFMergerTest extends TestCase
{
    /** @var string */
    private $resultDirectory;
    /** @var string */
    private $samplesDirectory;

    public function setUp(): void
    {
        $this->samplesDirectory = __DIR__ . '/pdfs/';
        $this->resultDirectory = __DIR__ . '/../var/';
    }

    public function testMergePdfAllPages()
    {
        $pdf = new PDFMerger();

        $pdf->addPDF($this->samplesDirectory . 'github_1.pdf')
            ->addPDF($this->samplesDirectory . 'github_2.pdf')
            ->merge('file', $this->resultDirectory . 'test_github_1.pdf');

        $this->verifyResult('result_all_pages.pdf', 'test_github_1.pdf');
    }

    public function testMergePdfSpecificPages()
    {
        $pdf = new PDFMerger();

        $pdf->addPDF($this->samplesDirectory . 'github_home.pdf', '1,2')
            ->addPDF($this->samplesDirectory . 'github_home.pdf', '5,6')
            ->merge('file', $this->resultDirectory . 'test_github_1.pdf');

        $this->verifyResult('result_specific_pages.pdf', 'test_github_1.pdf');
    }

    public function testMergePdfPagesRange()
    {
        $pdf = new PDFMerger();

        $pdf->addPDF($this->samplesDirectory . 'github_home.pdf', '1-3')
            ->addPDF($this->samplesDirectory . 'github_home.pdf', '6,7')
            ->merge('file', $this->resultDirectory . 'test_github_1.pdf');

        $this->verifyResult('result_pages_range.pdf', 'test_github_1.pdf');
    }

    public function testBadPageList()
    {
        $pdf = new PDFMerger();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Page number "2" out of available page range (1 - 1)');
        $pdf->addPDF($this->samplesDirectory . 'github_1.pdf', '1,2')
            ->merge('file', $this->resultDirectory . 'test_github_1.pdf');
    }

    public function testPageRangeTooBig()
    {
        $pdf = new PDFMerger();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Page number "3" out of available page range (1 - 2)');
        $pdf->addPDF($this->samplesDirectory . 'result_all_pages.pdf', '1-6')
            ->merge('file', $this->resultDirectory . 'test_github_1.pdf');
    }

    public function testBadPageRange()
    {
        $pdf = new PDFMerger();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage("Starting page, '5' is greater than ending page '2'.");
        $pdf->addPDF($this->samplesDirectory . 'result_all_pages.pdf', '5-2')
            ->merge('file', $this->resultDirectory . 'test_github_1.pdf');
    }

    public function testUnkownPdf()
    {
        $pdf = new PDFMerger();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf(
            "Could not locate PDF on '%sunknown.pdf'",
            $this->samplesDirectory
        ));
        $pdf->addPDF($this->samplesDirectory . 'unknown.pdf');
    }

    public function testEmptyPdfList()
    {
        $pdf = new PDFMerger();

        $this->expectException(RuntimeException::class);
        $this->expectExceptionMessage("No PDFs to merge.");
        $pdf->merge('file', $this->resultDirectory . 'test_github_1.pdf');
    }

    private function verifyResult(string $original, string $result): void
    {
        $this->assertFileExists($this->resultDirectory . $result);

        $this->assertEquals(
            filesize($this->samplesDirectory . $original),
            filesize($this->resultDirectory . $result)
        );
    }
}
