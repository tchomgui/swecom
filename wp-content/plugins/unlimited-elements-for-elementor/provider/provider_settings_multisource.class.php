<?php
/**
 * @package Unlimited Elements
 * @author unlimited-elements.com
 * @copyright (C) 2012 Unite CMS, All Rights Reserved. 
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * */
defined('UNLIMITED_ELEMENTS_INC') or die('Restricted access');

class UniteCreatorSettingsMultisource{
	
	private $settings;
	private $objAddon;
	private $arrPostFields;
	
	
	public function __construct(){
		
		//for autocomplete
		$this->objAddon	= new UniteCreatorAddon();
		
		$this->objAddon = null;
		
		$this->arrPostFields = array(
			"default"=>__("-- Item Default --","unlimited-elements-for-elementor"),
			"static_value"=>__("-- Static Value --","unlimited-elements-for-elementor"),
			"title"=>__("Post Title","unlimited-elements-for-elementor"),
			"alias"=>__("Post Name","unlimited-elements-for-elementor"),
			"intro"=>__("Post Intro","unlimited-elements-for-elementor") ,
			"content"=>__("Post Content","unlimited-elements-for-elementor"),
			"image"=>__("Post Featured Image","unlimited-elements-for-elementor"),
			"date"=>__("Post Date","unlimited-elements-for-elementor"),
			"link"=>__("Post Url","unlimited-elements-for-elementor"),
			"meta_field"=>__("Post Meta Field","unlimited-elements-for-elementor")
		);
		
		$this->arrPostFields = array_flip($this->arrPostFields);
		
	}
	
	
	/**
	 * set the settings
	 */
	public function setSettings(UniteCreatorSettings $settings){

		$this->settings = $settings;
		$this->objAddon = GlobalsProviderUC::$activeAddonForSettings;
		
	}
	
	
	/**
	 * add items multisource
	 */
	public function addItemsMultisourceSettings($name, $value, $title, $param){
		
		UniteFunctionsUC::validateNotEmpty($this->settings, "settings object");
		
		
		//------ items source ------
		
		$arrSource = array();
		
		$arrSource["items"] = __("Items", "unlimited-elements-for-elementor");
		$arrSource["posts"] = __("Posts", "unlimited-elements-for-elementor");
		
		/*
		$isWooActive = UniteCreatorWooIntegrate::isWooActive();
		if($isWooActive == true)
			$arrSource["products"] = __("Products", "unlimited-elements-for-elementor");
		
		$arrSource["terms"] = __("Terms", "unlimited-elements-for-elementor");
		
		$isAcfExists = UniteCreatorAcfIntegrate::isAcfActive();
		
		if($isAcfExists == true)
			$arrSource["acf_repeater"] = __("ACF Repeater", "unlimited-elements-for-elementor");
		
		$hasInstagram = HelperProviderCoreUC_EL::isInstagramSetUp();
		
		if($hasInstagram)
			$arrSource["instagram"] = __("Instagram", "unlimited-elements-for-elementor");
		
		*/
		
		$arrSource = array_flip($arrSource);

		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_DROPDOWN;
		
		$this->settings->addSelect($name."_source", $arrSource, __("Items Source", "unlimited-elements-for-elementor"), "items", $params);
		
		
		$this->addMultisourceConnectors_posts($name);
		
	}
	
	/**
	 * add multisource connectors
	 */
	private function addMultisourceConnectors_posts($name){
		
		if(empty($this->objAddon))
			return(false);

		$hasItems = $this->objAddon->isHasItems();

		if($hasItems == false)
			return(false);
		
		$paramsItems = $this->objAddon->getParamsItems();
		
		if(empty($paramsItems))
			return(false);
		
		$condition = array($name."_source"=>"posts");
		
		// --- hr before fields source
			
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_HR;
		
		//$params["elementor_condition"] = $arrCustomOnlyCondition;
		
		$this->settings->addHr($name."_before_posts_fields_connect",$params);
						
		// --- items source select 
		
		foreach($paramsItems as $itemParam){
			
			$this->putParamConnector_post($name, $itemParam, $condition);
		}

		//--------- h3 before meta ---------- 
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_HR;
		$params["elementor_condition"] = $condition;
		
		$this->settings->addHr($name."_hr_before_debug",$params);
		
		
		//--------- debug meta ---------- 
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_RADIOBOOLEAN;
		$params["description"] = __("Show the current post meta fields, turn off it after choose the right one", "unlimited-elements-for-elementor");
		$params["elementor_condition"] = $condition;
		
		$this->settings->addRadioBoolean($name."_show_metafields", __("Debug - Show Meta Fields", "unlimited-elements-for-elementor"), false, "Yes", "No", $params);
		
		
		
	}
	
	
	/**
	 * get post param connector
	 */
	private function putParamConnector_post($fieldName, $param, $condition){
		
		$title = UniteFunctionsUC::getVal($param, "title");
		
		if(empty($title))
			return(false);
			
		$name = UniteFunctionsUC::getVal($param, "name");
		
		if(empty($name))
			return(false);
		
		//-------------- select param ----------------
			
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_DROPDOWN;
		$params["elementor_condition"] = $condition;
		
		$text = $title. " ".__("Source", "unlimited-elements-for-elementor");
		
		$selectName = $fieldName."_posts_field_source_$name";
		
		$this->settings->addSelect($selectName, $this->arrPostFields, $text, "default", $params);
		
		
		//-------------- meta field ----------------
		
		$conditionMetaField = $condition;
		$conditionMetaField[$selectName] = "meta_field";
		
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_TEXTFIELD;
		$params["elementor_condition"] = $conditionMetaField;
		
		$text = $title. " ".__("Meta Field", "unlimited-elements-for-elementor");
		
		$this->settings->addTextBox($fieldName."_posts_field_meta_{$name}", "", $text, $params);
		
		//-------------- static value ----------------
		
		$conditionMetaField = $condition;
		$conditionStaticValue[$selectName] = "static_value";
				
		$params = array();
		$params["origtype"] = UniteCreatorDialogParam::PARAM_TEXTFIELD;
		$params["elementor_condition"] = $conditionStaticValue;
		$params["label_block"] = true;
		
		$text = $title. " ".__("Static Value", "unlimited-elements-for-elementor");
		
		$this->settings->addTextBox($fieldName."_posts_field_value_{$name}", "", $text, $params);
		
		
	}
	
	
	
}