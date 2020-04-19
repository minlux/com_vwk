<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="xml" indent="yes"
    doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"
    doctype-public="-//W3C//DTD XHTML 1.0 Transitional//EN" />


   <!-- overwrite build-in template -->
   <xsl:template match="text()">
   </xsl:template>


   <xsl:template match="ergebnisse">
      <div class="vwkergebnisse">
         <table>
            <tr>
               <th>Platz</th>
               <th>Name</th>
               <th>Ergebnisse</th>
            </tr>
            <xsl:apply-templates select="ergebnis" />
         </table>
      </div>
   </xsl:template>


   <xsl:template match="ergebnis">
      <tr>
         <td><xsl:value-of select="@platz" /></td>
         <td><xsl:value-of select="schutze" /></td>
         <td>
            <xsl:for-each select="wert">
               <span><xsl:value-of select="." /><xsl:text> </xsl:text><xsl:value-of select="@typ"/></span>
            </xsl:for-each>
         </td>
      </tr>
   </xsl:template>

</xsl:stylesheet>

