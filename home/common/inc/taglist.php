<?php
	$info = pathinfo($_SERVER['SCRIPT_NAME']);
	$fname = basename($_SERVER['SCRIPT_NAME'], '.'.$info['extension']);
	$html = "";
		if($fname=='sub_index' || $fname=='t_index'){
			// �����ƥ�����ڡ���
			$html = <<<EOD
			
			<div id="item_search">
				<p class="stitle">
					<span class="txt_1">ɽ�����</span>
					<span id="count">{$item_count}</span>
					<span class="txt_2">����</span>
					<img src="/common/img/arrow_gray.png" />
				</p>
				<p class="btn" id="searchbtn"><img src="/common/img/search.png" class="btn_search" alt="��︡��">��︡��</p>
				<div class="box list hidelist" id="searchcondition">
					<form name="form_tag_search" id="form_tag_search" action="{$_SERVER['SCRIPT_NAME']}" method="get">
						{$tag_data}
						<input type="hidden" name="addtag" value="">
					</form>
EOD;
			echo $html;
			
			$html = "";
			if(isset($_IS_TAG)){
				// �֥��ɰ����ڡ���
				$html .= $category_tag;
				
				if(isset($_SCENE)){
					// ���ݡ��ĥ������������ԥ��󡢥ס��ޡ�����ɥ�
					$html .= $scene_tag;
				}
			}
			$html .= $material_tag;
			$html .= $silhouette_tag;
			$html .= $cloth_tag;
			$html .= $size_tag;
			$html .= '</div>';
			$html .= '<div class="hidelist" id="searchcondition_clear"><a href="'.$_SERVER['SCRIPT_NAME'].'" class="btn_sub btn_clear">���ꥻ�å�</a></div>';
			$html .= '</div>';
			
			echo $html;
		}
?>
