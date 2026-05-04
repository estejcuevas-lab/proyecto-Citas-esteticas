<?xml version="1.0" encoding="UTF-8"?>
<!--
========================================================================
GUIA 8 - ACTIVIDAD 5: TRANSFORMACION DE DATOS CON XSLT
La hoja transforma la respuesta XML del servicio a una salida HTML legible.
========================================================================
-->
<xsl:stylesheet version="1.0"
    xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" indent="yes" encoding="UTF-8"/>

    <xsl:template match="/">
        <html>
            <head>
                <title>Respuesta de cita remota</title>
            </head>
            <body style="font-family: Georgia, serif; background:#f8f4ef; color:#2f241f; padding:24px;">
                <div style="max-width:760px; margin:0 auto; background:#fffaf5; border:1px solid #d8c4af; border-radius:20px; padding:24px;">
                    <h1 style="margin-top:0; color:#6d4c35;">Respuesta del servicio remoto</h1>
                    <p><strong>Estado:</strong> <xsl:value-of select="appointmentMessage/body/status"/></p>
                    <p><strong>Mensaje:</strong> <xsl:value-of select="appointmentMessage/body/message"/></p>

                    <h2 style="color:#6d4c35;">Detalle de la cita</h2>
                    <ul>
                        <li><strong>Cliente:</strong> <xsl:value-of select="appointmentMessage/body/appointment/client_name"/></li>
                        <li><strong>Negocio:</strong> <xsl:value-of select="appointmentMessage/body/appointment/business_name"/></li>
                        <li><strong>Servicio:</strong> <xsl:value-of select="appointmentMessage/body/appointment/service_name"/></li>
                        <li><strong>Fecha:</strong> <xsl:value-of select="appointmentMessage/body/appointment/appointment_date"/></li>
                        <li><strong>Hora:</strong> <xsl:value-of select="appointmentMessage/body/appointment/start_time"/></li>
                    </ul>
                </div>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
