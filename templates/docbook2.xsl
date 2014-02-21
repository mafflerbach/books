<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0"
                xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

  <xsl:output method="xml" indent="yes"/>

  <xsl:template match="html">
    <book>
      <info>
        <title>
          <xsl:value-of select="head/title"/>
        </title>
        <author>
          <personname>
            <firstname>Jane</firstname>
            <surname>Doe</surname>
          </personname>
        </author>
        <copyright>
          <year>2010</year>
          <holder>Jane Doe</holder>
        </copyright>
      </info>

      <xsl:call-template name="chapter">
        <xsl:with-param name="node" select="body/div"/>
      </xsl:call-template>
    </book>
  </xsl:template>

  <xsl:template name="chapter">
    <xsl:param name="node"/>
    <xsl:for-each select="$node">
      <chapter>
        <title>
          <xsl:value-of select="h2"/>
        </title>
        <xsl:call-template name="section">
          <xsl:with-param name="node" select="div"/>
        </xsl:call-template>
      </chapter>
    </xsl:for-each>

  </xsl:template>

  <xsl:template name="section">
    <xsl:param name="node"/>
    <xsl:for-each select="$node">
      <section>
        <title>
          <xsl:value-of select="h3"/>
        </title>

        <xsl:call-template name="param">
          <xsl:with-param name="node" select="p"/>
        </xsl:call-template>
      </section>

    </xsl:for-each>

  </xsl:template>

  <xsl:template name="param">
    <xsl:param name="node"/>
    <xsl:for-each select="$node">
      <para>
        <xsl:value-of select="."/>
      </para>
    </xsl:for-each>
  </xsl:template>

</xsl:stylesheet>