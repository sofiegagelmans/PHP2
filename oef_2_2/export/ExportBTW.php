<?php

class ExportCSV_BTW extends AbstractCSVExport
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
        $this->output = $this->PrintHeader("eu_btw_codes");
        $this->output .= $this->PrintColumnHeadings();
        $this->output .= $this->PrintBody();

        $this->ToCaps();

        print $this->output;
    }

    protected function PrintColumnHeadings()
    {
        return "Land;BTW Code;Hyperlink;\r\n";
    }

    protected function PrintBody()
    {
        $data = $this->DBManager->GetData( "select * from eu_btw_codes" );

        $result = "";

        foreach ( $data as $row )
        {
            $land = str_replace(" ", "_", trim($row['eub_land']));
            $hyperlink = "https://nl.wikipedia.org/wiki/" . $land;
            $result .= $land . ";" . $row['eub_code']. ";" . $hyperlink . ";" . "\r\n";
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