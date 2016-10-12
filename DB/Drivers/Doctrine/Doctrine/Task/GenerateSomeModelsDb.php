<?php
/*
 *  $Id: GenerateSomeModelsDb.php 2761 2007-10-07 23:42:29Z zYne $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <http://www.phpdoctrine.org>.
 */

/**
 * Doctrine_Task_GenerateSomeModelsDb
 *
 * @package     Doctrine
 * @subpackage  Task
 * @license     http://www.opensource.org/licenses/lgpl-license.php LGPL
 * @link        www.phpdoctrine.org
 * @since       1.0
 * @version     $Revision: 2761 $
 * @author      Iñaki Abete <inakiabt@gmail.com>
 */
class Doctrine_Task_GenerateSomeModelsDb extends Doctrine_Task
{
    public $description          =   'Generates some of your Doctrine_Record definitions from your existing database connections.',
           $requiredArguments    =   array('options'        =>  'Specify config generated files places.',
                                           'models_path'    =>  'Specify path to your Doctrine_Record definitions.',
                                           'tables_array'   =>  'Specify wich tables do you want to generate your Doctrine_Record definitions, comma separatted without spaces.'),
           $optionalArguments    =   array();
    
    public function execute()
    {
        BarakusDoctrine::generateSomeModelsFromDb($this->getArgument('models_path'), (array) explode(',', $this->getArgument('tables_array')), (array) $this->getArgument('connection'), (array) $this->getArgument('options'));
        
        $this->dispatcher->notify('Generated models successfully from databases');
    }
}
