<#-- GetFeatureInfo (INFO_FORMAT=text/html) output read by js-map/map.js:
     <feature>, <feature-id>, and one tag per attribute (e.g. <zone>, <index>). -->
<#list features as feature>
	<feature>
		<type>${type.name}</type>
		<feature-id>${feature.fid}</feature-id>
		<attributes>
<#list feature.attributes as attribute>
<#if !attribute.isGeometry>
			<${attribute.name?lower_case}>${attribute.value?xml}</${attribute.name?lower_case}>
</#if>
</#list>
		</attributes>
	</feature>
</#list>
