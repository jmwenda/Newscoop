<?php
// Call SectionTest::main() if this source file is executed directly.
if (!defined("PHPUnit_MAIN_METHOD")) {
    define("PHPUnit_MAIN_METHOD", "SectionTest::main");
}

require_once('PHPUnit/Framework/TestCase.php');
require_once('PHPUnit/Framework/TestSuite.php');

require_once('template_engine/classes/ComparisonOperation.php');
require_once('template_engine/classes/Operator.php');
require_once('classes/Section.php');

require_once('set_path.php');
require_once('db_connect.php');

/**
 * Test class for Section.
 * Generated by PHPUnit_Util_Skeleton on 2007-07-02 at 13:27:00.
 */
class SectionTest extends PHPUnit_Framework_TestCase
{
    /**
     * Runs the test methods of this class.
     *
     * @access public
     * @static
     */
    public static function main()
    {
        require_once('PHPUnit/TextUI/TestRunner.php');

        $suite  = new PHPUnit_Framework_TestSuite('SectionTest');
        $result = PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * Sets up the fixture, for example, open a network connection.
     * This method is called before a test is executed.
     *
     * @access protected
     */
    protected function setUp()
    {
    }

    /**
     * Tears down the fixture, for example, close a network connection.
     * This method is called after a test is executed.
     *
     * @access protected
     */
    protected function tearDown()
    {
    }

    public function testGetList()
    {
        global $g_ado_db;

        // builds basic parameters
        $leftOperand = 'IdPublication';
        $rightOperand = 1;
        $operator = new Operator('is', 'sql');
        $comparisonOperationObj1 = new ComparisonOperation($leftOperand,
                                                           $operator,
                                                           $rightOperand);
        $leftOperand = 'IdLanguage';
        $rightOperand = 1;
        $operator = new Operator('is', 'sql');
        $comparisonOperationObj2 = new ComparisonOperation($leftOperand,
                                                           $operator,
                                                           $rightOperand);
        $leftOperand = 'NrIssue';
        $rightOperand = 1;
        $operator = new Operator('is', 'sql');
        $comparisonOperationObj3 = new ComparisonOperation($leftOperand,
                                                           $operator,
                                                           $rightOperand);

        // builds the constraint
        $leftOperand = 'Number';
        $rightOperand = 60;
        $operator = new Operator('equal_smaller', 'sql');
        $comparisonOperationObj4 = new ComparisonOperation($leftOperand,
                                                           $operator,
                                                           $rightOperand);

        // sets the params for Section::GetList()
        $params = array($comparisonOperationObj1,
                        $comparisonOperationObj2,
                        $comparisonOperationObj3,
                        $comparisonOperationObj4);
        $order = array();
        $limitStart = 0;
        $limitOffset = 0;

        // gets the list of sections
        $sectionsList = Section::GetList($params, $order, $limitStart, $limitOffset);

        $sList = array();
        $sql = "SELECT * FROM Sections WHERE IdPublication = 1 AND NrIssue = 1 AND IdLanguage = 1 AND Number <= 60";
        $sections = $g_ado_db->GetAll($sql);
        foreach ($sections as $section) {
            $sList[] = new Section($section['IdPublication'],
                                   $section['NrIssue'],
                                   $section['IdLanguage'],
                                   $section['Number']);
        }

        $this->assertEquals(sizeof($sList), sizeof($sectionsList));

        $i = 0;
        foreach ($sectionsList as $section) {
            $this->assertEquals($sList[$i]->getName(), $section->getName());
            $i++;
        }
    } // fn testGetList

    public function testGetListBadParameter()
    {
        global $g_ado_db;

        // builds the constraint
        $leftOperand = 'ShortName';
        $rightOperand = 'opensource';
        $operator = new Operator('is', 'sql');
        $comparisonOperationObj = new ComparisonOperation($leftOperand,
                                                          $operator,
                                                          $rightOperand);

        // sets the params for Section::GetList()
        $params = array($comparisonOperationObj);
        $order = array('Name' => 'DESC');
        $limitStart = 0;
        $limitOffset = 0;

        $this->assertEquals(null, Section::GetList($params, $order, $limitStart, $limitOffset));
    } // fn testGetListBadParameter

} // class SectionTest

// Call SectionTest::main() if this source file is executed directly.
if (PHPUnit_MAIN_METHOD == "SectionTest::main") {
    SectionTest::main();
}
?>