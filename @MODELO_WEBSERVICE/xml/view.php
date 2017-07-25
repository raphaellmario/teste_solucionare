<DataSet xmlns="http://acessoweb.brasilia.me:8080/vsap/webservice/xml/publicacoesNovasTodosAuto.php">
    <xs:schema xmlns="" xmlns:xs="http://www.w3.org/2001/XMLSchema" xmlns:msdata="urn:schemas-microsoft-com:xml-msdata"
      id="NewDataSet">
        <xs:element name="NewDataSet" msdata:IsDataSet="true" msdata:UseCurrentLocale="true">
            <xs:complexType>
                <xs:choice minOccurs="0" maxOccurs="unbounded">
                    <xs:element name="Table">
                        <xs:complexType>
                            <xs:sequence>
                                <xs:element name="Empresa" type="xs:string" minOccurs="0"/>
                                <xs:element name="Parte1" type="xs:string" minOccurs="0"/>
                                <xs:element name="Parte2" type="xs:string" minOccurs="0"/>
                                <xs:element name="DataPublicacao" type="xs:dateTime" minOccurs="0"/>
                                <xs:element name="Processo" type="xs:string" minOccurs="0"/>
                                <xs:element name="Diario" type="xs:string" minOccurs="0"/>
                                <xs:element name="Pagina" type="xs:int" minOccurs="0"/>
                                <xs:element name="Orgao" type="xs:string" minOccurs="0"/>
                                <xs:element name="Juizo" type="xs:string" minOccurs="0"/>
                                <xs:element name="Andamento" type="xs:string" minOccurs="0"/>
                                <xs:element name="CodigoRelacional" type="xs:long" minOccurs="0"/>
                                <xs:element name="DataCirculacao" type="xs:dateTime" minOccurs="0"/>
                            </xs:sequence>
                        </xs:complexType>
                    </xs:element>
                </xs:choice>
            </xs:complexType>
        </xs:element>
    </xs:schema>
    <diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xmldiffgram-v1">
        <NewDataSet xmlns="">
            <?php foreach($resultados as $i => $res) : ?>
            <Table diffgr:id="Table<?=($i+1);?>" msdata:rowOrder="<?=$i?>">
                    <Empresa><?=$res['NOME_ESCRITORIO']?></Empresa>
                    <Parte1/>
                    <Parte2/>
                    <DataPublicacao><?=$res['DATA_PUBLICACAO']?></DataPublicacao>
                    <Processo><?=$res['N_PROCESSO']?></Processo>
                    <Diario><?=$res['VARA']?></Diario>
                    <Pagina>0</Pagina>
                    <Orgao><?=$res['TRIBUNAL']?></Orgao>
                    <Juizo/>
                    <Andamento><?=$res['PUBLICACAO']?></Andamento>
                    <CodigoRelacional><?=$res['PROTOCOLO']?></CodigoRelacional>
                    <DataCirculacao><?=$res['DATA_DIVULGACAO']?></DataCirculacao>
            </Table>
            <?php endforeach; ?>
        </NewDataSet>
    </diffgr:diffgram>
</DataSet>
