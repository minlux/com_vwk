<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
                xmlns:wmshot = 'urn:crystal-reports:schemas'>
<xsl:output method="xml" indent="yes" encoding="UTF-8" />

   <xsl:variable name="var_Digit">0123456789</xsl:variable>
   <xsl:variable name="var_Asterisk">**********</xsl:variable>


   <!-- overwrite build-in template -->
   <xsl:template match="text()">
   </xsl:template>


   <xsl:template match="/wmshot:FormattedReport">
      <xsl:element name="vwk">
         <xsl:element name="ergebnisse">
            <xsl:apply-templates />
         </xsl:element>
      </xsl:element>
   </xsl:template>


   <xsl:template match="wmshot:FormattedReportObjects">
      <!-- "Ringlisten" haben keinen FormattedReportObject Knoten mit Attribute @FieldName='{@RingeTeiler}' -->
      <xsl:variable name="var_RingeTeiler">
         <xsl:choose>
            <xsl:when test="wmshot:FormattedReportObject[@FieldName='{@RingeTeiler}']">
               <xsl:apply-templates select="wmshot:FormattedReportObject[@FieldName='{@RingeTeiler}']"/>
            </xsl:when>
            <xsl:otherwise>
               <xsl:text>R</xsl:text>
            </xsl:otherwise>
         </xsl:choose>
      </xsl:variable>
      <!-- "Teilerlisten" haben einen FormattedReportObject Knoten mit Attribute @FieldName='{@RingeTeiler}', aber der ist leer -->
      <xsl:variable name="var_Typ">
         <xsl:choose>
            <xsl:when test="$var_RingeTeiler = ''">
               <xsl:text>T</xsl:text>
            </xsl:when>
            <xsl:otherwise>
               <xsl:value-of select="$var_RingeTeiler"/>
            </xsl:otherwise>
         </xsl:choose>
      </xsl:variable>

      <xsl:element name="ergebnis">
         <xsl:attribute name="platz"><xsl:apply-templates select="wmshot:FormattedReportObject[@FieldName='{Ergebnisse.Platz}']"/></xsl:attribute>

         <xsl:element name="schutze"><xsl:apply-templates select="wmshot:FormattedReportObject[@FieldName='{Ergebnisse.Name}']"/></xsl:element>
<!--         <xsl:element name="verein"><xsl:apply-templates select="wmshot:FormattedReportObject[@FieldName='{Ergebnisse.Verein}']"/></xsl:element> -->
         <xsl:for-each select="wmshot:FormattedReportObject">
            <xsl:variable name="var_FieldName"><xsl:value-of select="translate(@FieldName, $var_Digit, $var_Asterisk)" /></xsl:variable>

            <xsl:if test="$var_FieldName = '{@Erg*}'">
               <xsl:variable name="var_ValueString"><xsl:value-of select="translate(wmshot:FormattedValue, ',.', '.')"/></xsl:variable>
               <xsl:if test="$var_ValueString != ''">
                   <xsl:variable name="var_Value"><xsl:value-of select="format-number($var_ValueString, '#.##')"/></xsl:variable>

                  <xsl:element name="wert">
                     <xsl:attribute name="typ"><xsl:value-of select="$var_Typ"/></xsl:attribute>
                     <xsl:value-of select="$var_Value"/>
                  </xsl:element>
               </xsl:if>
            </xsl:if>
         </xsl:for-each>
      </xsl:element>
   </xsl:template>


   <xsl:template match="wmshot:FormattedReportObject">
      <xsl:value-of select="wmshot:FormattedValue"/>
   </xsl:template>


</xsl:stylesheet>

