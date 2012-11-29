<?php
/**
 * Api module for querying MessageGroups.
 *
 * @file
 * @author Niklas Laxström
 * @copyright Copyright © 2010, Niklas Laxström
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */

/**
 * Api module for querying MessageGroups.
 *
 * @ingroup API TranslateAPI
 */
class ApiQueryMessageGroups extends ApiQueryBase {

	public function __construct( $query, $moduleName ) {
		parent::__construct( $query, $moduleName, 'mg' );
	}

	public function getCacheMode( $params ) {
		return 'public';
	}

	public function execute() {
		$params = $this->extractRequestParams();
		if ( $params['format'] === 'tree' ) {
			$groups = MessageGroups::getGroupStructure();
		} else {
			$groups = MessageGroups::getAllGroups();
		}

		$result = $this->getResult();

		foreach ( $groups as $mixed ) {
			$a = $this->formatGroup( $mixed );
			$id = $a['id'];

			$result->setIndexedTagName( $a, 'group' );

			// TODO: Add a continue?
			$fit = $result->addValue( array( 'query', $this->getModuleName() ), null, $a );
			if ( !$fit ) {
				$this->setWarning( 'Could not fit all groups in the resultset.' );
				// Even if we're not going to give a continue, no point carrying on if the result is full
				break;
			}
		}

		$result->setIndexedTagName_internal( array( 'query', $this->getModuleName() ), 'group' );
	}

	/**
	 * @param array|MessageGroup $mixed
	 * @return array
	 */
	protected function formatGroup( $mixed ) {
		// Default
		$g = $mixed;
		$subgroups = array();

		// Format = tree and has subgroups
		if ( is_array( $mixed ) ) {
			$g = array_shift( $mixed );
			$subgroups = $mixed;
		}

		$a = array();
		$a['id'] = $g->getId();
		$a['label'] = $g->getLabel();
		$a['description'] = $g->getDescription();
		$a['class'] = get_class( $g );
		$a['exists'] = $g->exists();

		if ( $subgroups !== array() ) {
			foreach( $subgroups as $sg ) {
				$a['groups'][] = $this->formatGroup( $sg );
			}
			$result = $this->getResult();
			$result->setIndexedTagName( $a['groups'], 'group' );
		}

		return $a;
	}

	public function getAllowedParams() {
		return array(
			'format' => array(
				ApiBase::PARAM_TYPE => array( 'flat', 'tree' ),
				ApiBase::PARAM_DFLT => 'flat',
			)
		);
	}

	public function getParamDescription() {
		return array(
			'format' => 'In a tree format message groups can exist multiple places in the tree.',
		);
	}


	public function getDescription() {
		return 'Return information about message groups';
	}

	protected function getExamples() {
		return array(
			'api.php?action=query&meta=messagegroups',
		);
	}

	public function getVersion() {
		return __CLASS__ . ': ' . TRANSLATE_VERSION;
	}
}
