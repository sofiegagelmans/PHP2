<?php

class ExportImages extends AbstractCSVExport
{
    //niet overgeërfde properties
    private $DBManager;
    private $caps;
    private $output;

    public function __construct(DBManager $DBManager, $caps = null)
    {
        $this->DBManager = $DBManager;
        $this->caps = $caps;
    }

    public function Generate()
    {
        $this->output = $this->PrintHeader("images");
        $this->output .= $this->PrintColumnHeadings();
        $this->output .= $this->PrintBody();

        $this->ToCaps();

        print $this->output;
    }

    protected function PrintColumnHeadings()
    {
        return "Id;Filename;Title;Width;Height;Published;LanId;Date\r\n";
    }

    protected function PrintBody()
    {
        $data = $this->DBManager->GetData( "select * from images", $fetch_options = "assoc" );

        $result = "";

        foreach ( $data as $row )
        {
            $result .= implode(";", $row) . "\r\n";
        }

        return $result;
    }

    //niet overgeërfde methode
    private function ToCaps()
    {
        if ( $this->caps ) {
            $this->output = strtoupper($this->output);
        }
    }

}