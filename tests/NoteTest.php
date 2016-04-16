<?php
class NoteTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testAvoidDuplication()
    {
        $noteController = new \App\Http\Controllers\NoteController();
        $testFile = 'testFile';
        Storage::disk('local')->prepend($testFile, 'This is test file.');
        $path = $noteController->avoid_duplication($testFile);
        $this->assertEquals('testFile-1', $path);
        Storage::disk('local')->delete($testFile);
    }
}
