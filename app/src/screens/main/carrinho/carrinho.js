import React, { Component } from 'react';
import { View, StatusBar, TouchableOpacity, SafeAreaView, Alert, Modal, ScrollView } from 'react-native';
import { Container, Item, Input, Picker, Label, Icon, Text, Content, FooterTab, Button, ListItem, CheckBox, Body } from 'native-base';
import { setCar, setCarEmp } from './../../../redux/actions/';
import { bindActionCreators } from 'redux';
import { connect } from 'react-redux';
import { openDatabase } from 'react-native-sqlite-storage';
import ListCarrinho from './../../fragments/listCarrinho';
import { MAlert } from './../../../utilites/utilites';
import api from './../../../utilites/api'
import Carregando from './../../fragments/carregando2'
var db = openDatabase({ name: 'delivery.db', createFromLocation: 1 });
import TimePicker from "react-native-24h-timepicker";
import DatePicker from 'react-native-datepicker';


class Carrinho extends Component {
  state = {

    enderecoNome: '',
    enderedoEnd: '',
    enderedoNumero: '',
    enderecoValor: 0,
    enderecoBairro: '',
    cupom: '',
    nf: 'Sim',
    obs: '',
    time: "",
    date: '',
    ativo: true,

    vlEntrega: '0',
    vlCupom: '0',
    vl: '0',

    pagamento: 'Dinheiro',
    isCupom: false,
    isLoading: false,
    userNome: '',
    userTelefone: '',
    isMoto: false,
    isAgend: false,
    isTroco: true,
    isFisc: false,
    vlTroco: '',
    notaFiscal: '',
    qtdFidel: "9",
    descFidel: 10,
    fidel: 0,
    aplicFidelidade: false,
    vlMinimo: 15,


  }

  vl = 0;
  aplica = true;

  async componentDidMount() {
    this.setState({ isLoading: true });

    db.transaction(tx => {
      tx.executeSql('SELECT * FROM user', [], (tx, results) => {
        console.log('item: ', results.rows.item(0));
        if (results.rows.length > 0) {
          this.setState({
            userNome: results.rows.item(0).id + '-' + results.rows.item(0).nome,
            userTelefone: results.rows.item(0).telefone,
          });
          this.isAtivo();
        } else {
        }
      });
    });

    db.transaction(tx => {
      tx.executeSql('SELECT * FROM endereco', [], (tx, results) => {
        console.log('item: ', results.rows.item(0));
        if (results.rows.length > 0) {
          this.setState({ endereco: results.rows.item(0) });
          console.log('Empp', results.rows.item(0));
          this.setState({
            enderecoNome: results.rows.item(0).titulo,
            enderedoEnd: results.rows.item(0).end,
            enderecoBairro: results.rows.item(0).bairro,
            enderecoValor: results.rows.item(0).preco,
            enderedoNumero: results.rows.item(0).numero,
          });
          this.getServidor();
        } else {
          this.setState({ isLoading: false });
        }
      });
    });
  }

  async isAtivo() {
    try {
      const response3 = await api.post('ws/StatusServico.php');
      console.log('resposta2: ', response3.data)
      if (response3.status === 200) {
        if (response3.status !== '[]') {
          this.setState({
            ativo: response3.data.Codigo === '5' ? true : false
          });
        }

      } else {
        MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
      }

    } catch (error) {
      MAlert('Erro na comunicação com servidor!', 'Atenção', false);
    }
  }

  async getServidor() {
    console.log("Mes: ", new Date().getUTCMonth());

    this.props.carr.carrinho.map(i => (
      this.vl = 0
    ));

    this.props.carr.carrinho.map(i => (
      this.vl = parseFloat(this.vl) + parseFloat(i.preco)
    ));


    this.setState({ time: new Date().getHours() + ":" + new Date().getMinutes() })
    if (this.state.enderecoBairro !== '0') {
      try {
        const data = new FormData();
        data.append('bairro', this.state.enderecoBairro);
        data.append('Cliente', this.state.userNome);
        console.log('resposta1: ', this.state.enderecoBairro)
        const response = await api.post('ws/getVlEntrega.php', data);
        //const response3 = await api.post('ws/StatusServico.php');
        const response4 = await api.post('ws/getFidel.php', data);
        
        if (response.status === 200) {
          if (response.status !== '[]') {
            this.setState({
              vlEntrega: response.data.valor,
              fidel: response4.data.compras,
            });

            console.log('Aplica pontos: ', this.state.fidel)
            if (this.state.fidel === this.state.qtdFidel) {
              var x = (parseFloat(this.vl) * parseFloat(this.state.descFidel)) / 100
              this.setState({
                vlCupom: x,
                aplicFidelidade: true
              });
            }


          }

        } else {
          MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
        }

      } catch (error) {
        console.log('eroo:: ', error)
        MAlert('Erro na comunicação com servidor!', 'Atenção', false);
      }
    } else {
      this.setState({
        vlEntrega: '0',
        enderedoEnd: 'Retirada no local'
      });
    }

    this.setState({ isLoading: false });
  }

  handleCupom = async () => {
    this.setState({ isLoading: true });
    try {
      const data = new FormData();
      data.append('titulo', this.state.cupom);

      const response = await api.post('ws/ValidCupom.php', data);
      console.log('resposta: ', response.data)
      if (response.status === 200) {
        if (response.data.Descricao !== 'Cupom invalido') {
          var x = (parseFloat(this.vl) * parseFloat(response.data.percent)) / 100
          this.setState({
            vlCupom: x,
            isCupom: true
          });
        } else {
          MAlert(response.data.Descricao, 'Atenção', false);
        }
      } else {
        MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
      }

    } catch (error) {
      console.log('eroo ', error)
      MAlert('Erro na comunicação com servidor!', 'Atenção', false);
    }

    this.setState({ isLoading: false });

  }

  async handlleEnviarPedido() {
    this.setState({ isLoading: true });
    var teste = '';
    var order = {}
    var Capa = [];
    console.log("Hora ", this.state.time)


    try {
      Capa = [...Capa, {
        cliente: this.state.userNome,
        endereco: this.state.enderedoEnd + ', ' + this.state.enderedoNumero + ', ' + this.state.enderecoBairro,
        telefone: this.state.userTelefone,
        valor: (parseFloat(this.vl) + parseFloat(this.state.vlEntrega)) - parseFloat(this.state.vlCupom),
        pagamento: this.state.pagamento, entrega: this.state.vlEntrega,
        obs: (this.state.obs !== "" ? " Entrega para as: " + this.state.time + " do dia: " + this.state.date : "") +
          (this.state.vlTroco !== "" ? " -- Troco para: R$ " + this.state.vlTroco : "") +
          (this.state.notaFiscal !== "" ? " -- NFe para: " + this.state.notaFiscal : ""),
        bairro: this.state.enderecoBairro,
        cupom: this.state.vlCupom !== '0' ? this.state.cupom : '',
        aplicFidelidade: this.state.aplicFidelidade
      }];
      var Itens = [];

      var Temp = this.props.carr.carrinho;

      for (var i = 0; i < Temp.length; i++) {
        var x = '';
        console.log('Ret ', i)
        var Temp2 = this.props.carr.carrinho[i].motagem;

        for (var j = 0; j < Temp2.length; j++) {
          x = x + '(' + this.props.carr.carrinho[i].motagem[j] + ": ";
          var Temp3 = this.props.carr.carrinho[i].motagem[j];
          for (var y = 0; y < this.props.carr.carrinho[i][Temp3].length; y++) {
            x = x + this.props.carr.carrinho[i][Temp3][y] + ', ';
          }
          x = x + ') ';
        }

        Itens = [...Itens, {
          produto: this.props.carr.carrinho[i].id + ' - ' + this.props.carr.carrinho[i].nome, quantidade: this.props.carr.carrinho[i].quant, valor: this.props.carr.carrinho[i].preco,
          observacao: this.props.carr.carrinho[i].observacao, adcionais: x
        }]


      }

      // console.log('Capa ', JSON.stringify(Capa))
      // console.log('Itens ', JSON.stringify(Itens))

      const data = new FormData();
      data.append('JSon', JSON.stringify(Capa));
      data.append('JSon2', JSON.stringify(Itens));
      console.log('Env ', JSON.stringify(Capa))
      console.log('Env2 ', JSON.stringify(Itens))
      const response = await api.post('ws/finalizar_pedido.php', data);
      console.log('Ret ', response.data)


      if (response.status === 200 && response.data.Codigo === '100') {
        console.log('Pedido ', response.data);
        var arr = [];

        const {
          setCarEmp
        } = this.props;

        setCarEmp(arr);

        Alert.alert(
          "Tudo certo",
          "Seu pedido foi realizado com sucesso, aguarde o estabelecimento aceitar seu pedido!",
          [
            {
              text: 'Acompanhar pedido',
              onPress: () => {
                this.props.navigation.navigate('Pedidos');
              },
              style: 'cancel',
            },
            {
              text: 'Ok', onPress: async () => {

              }
            },
          ],
          { cancelable: false },
        );
      } else {
        MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
      }

    } catch (error) {
      console.log('Erro-> ', error)
      MAlert('Erro na comunicação com servidor!', 'Atenção', false);
    }

    this.setState({ isLoading: false });


  }

  retornaAdc(i) {
    var Temp = this.props.carr.carrinho;

    var x = '';


    console.log('Ret ', i)
    var Temp2 = this.props.carr.carrinho[i].motagem;

    for (var j = 0; j < Temp2.length; j++) {
      x = x + '(' + this.props.carr.carrinho[i].motagem[j] + ": ";
      var Temp3 = this.props.carr.carrinho[i].motagem[j];
      for (var y = 0; y < this.props.carr.carrinho[i][Temp3].length; y++) {
        x = x + this.props.carr.carrinho[i][Temp3][y] + ', ';
      }
      x = x + ') ';
    }

    return x;

  }


  form() {

    this.props.carr.carrinho.map(i => (
      this.vl = 0
    ));

    this.props.carr.carrinho.map(i => (
      this.vl = parseFloat(this.vl) + parseFloat(i.preco)
    ));

    return (
      <SafeAreaView>
        <View>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', paddingTop: 5, paddingLeft: 5, paddingRight: 15, }}>
            <View>
              <Text style={{ fontSize: 28, fontWeight: "bold", marginLeft: 15, marginTop: 15 }}>Carrinho</Text>
            </View>
          </View>
          <Text style={{ fontSize: 15, fontWeight: "bold", marginLeft: 20, marginTop: 25 }}>
            Itens do carrinho
          </Text>
          <View style={{ paddingLeft: 5, paddingRight: 20, }}>
            {this.props.carr.carrinho.map((i, d) => (
              <ListCarrinho qtd={i.quant} nome={i.nome} preco={i.preco} pos={d} adcionais={this.retornaAdc(d)} />
            ))}
          </View>
        </View>

        <View style={{ marginLeft: 21, marginTop: 20, paddingRight: 20, }}>
          <View style={{ marginBottom: 0, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' }}>
            <Text style={{ fontWeight: "bold", }}>Cupom de desconto</Text>
          </View>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
            <Item style={{ width: '80%' }}>
              <Input
                editable={!this.state.isCupom}
                value={this.state.cupom}
                onChangeText={cupom => this.setState({ cupom })}
              />
            </Item>
            {this.state.isCupom ? null :
              <TouchableOpacity style={{ width: 50, height: 51, marginLeft: 15, flexDirection: 'row', alignItems: 'center', justifyContent: 'space-between' }}
                onPress={this.handleCupom}
              >
                <View></View>
                <Icon type='AntDesign' name='check' style={{ fontSize: 18 }} />
              </TouchableOpacity>
            }
          </View>
        </View>

        {/* <View style={{ marginLeft: 21, marginTop: 20, paddingRight: 20, }}>
          <View style={{ marginBottom: 0, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' }}>
            <Text style={{ fontWeight: "bold", }}>Cartão fidelidade ( {this.state.fidel} / 10)</Text>
          </View>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', marginTop: 20 }}>
            <Icon type='AntDesign' name={this.state.fidel >= 1 ? 'checkcircle' : 'closecircle'} style={{ fontSize: 18, color: this.state.fidel >= 1 ? 'green' : '#D3D3D3' }} />
            <Icon type='AntDesign' name={this.state.fidel >= 2 ? 'checkcircle' : 'closecircle'} style={{ fontSize: 18, color: this.state.fidel >= 2 ? 'green' : '#D3D3D3' }} />
            <Icon type='AntDesign' name={this.state.fidel >= 3 ? 'checkcircle' : 'closecircle'} style={{ fontSize: 18, color: this.state.fidel >= 3 ? 'green' : '#D3D3D3' }} />
            <Icon type='AntDesign' name={this.state.fidel >= 4 ? 'checkcircle' : 'closecircle'} style={{ fontSize: 18, color: this.state.fidel >= 4 ? 'green' : '#D3D3D3' }} />
            <Icon type='AntDesign' name={this.state.fidel >= 5 ? 'checkcircle' : 'closecircle'} style={{ fontSize: 18, color: this.state.fidel >= 5 ? 'green' : '#D3D3D3' }} />
            <Icon type='AntDesign' name={this.state.fidel >= 6 ? 'checkcircle' : 'closecircle'} style={{ fontSize: 18, color: this.state.fidel >= 6 ? 'green' : '#D3D3D3' }} />
            <Icon type='AntDesign' name={this.state.fidel >= 7 ? 'checkcircle' : 'closecircle'} style={{ fontSize: 18, color: this.state.fidel >= 7 ? 'green' : '#D3D3D3' }} />
            <Icon type='AntDesign' name={this.state.fidel >= 8 ? 'checkcircle' : 'closecircle'} style={{ fontSize: 18, color: this.state.fidel >= 8 ? 'green' : '#D3D3D3' }} />
            <Icon type='AntDesign' name={this.state.fidel >= 9 ? 'checkcircle' : 'closecircle'} style={{ fontSize: 18, color: this.state.fidel >= 9 ? 'green' : '#D3D3D3' }} />
          </View>
        </View> */}

        <View style={{ marginLeft: 21, marginTop: 25, paddingRight: 20, }}>
          <View style={{ marginBottom: 0, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' }}>
            <Text style={{ fontWeight: "bold", }}>Local da entrega</Text>
          </View>
          <View>
            <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
              <Text style={{ fontSize: 15, fontWeight: 'bold' }}>{this.state.enderedoEnd} - {this.state.enderecoNome}</Text>
            </View>
          </View>
          <View style={{ marginTop: 10, flexDirection: 'row', justifyContent: 'space-between' }}>
            <TouchableOpacity onPress={() => {
              this.props.navigation.navigate('Endereco', {
                quemChamou: 'Carrinho'
              });
            }}>
              <Text style={{ color: 'blue' }}>Mudar endereço</Text>
            </TouchableOpacity>
            <TouchableOpacity onPress={() => {
              this.setState({
                enderecoNome: 'Retirada no local',
                enderedoEnd: 'Retirada no local',
                enderedoNumero: '0',
                enderecoValor: 0,
                enderecoBairro: '0 - local',
                vlEntrega: '0'
              });
              MAlert('Tudo bem, você irá retirar o pedido em nosso endereço!', 'Alerta', false);
            }
            }>
              <Text style={{ color: 'blue' }}>Retirada no local</Text>
            </TouchableOpacity>
          </View>
        </View>

        <View style={{ marginLeft: 21, marginTop: 25, }}>
          <View style={{ marginBottom: 0, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' }}>
            <Text style={{ fontWeight: "bold", }}>Meio de pagamento</Text>
          </View>
          <View>
            <View
              style={{ width: '103%', marginTop: 10 }}>
              <Item regular style={{ marginLeft: -3, marginRight: 30 }}>
                <Picker
                  mode="dropdown"
                  iosHeader="Selecione"
                  iosIcon={<Icon name="arrow-down" />}
                  selectedValue={this.state.pagamento}
                  onValueChange={this.onValueChange.bind(this)}
                >
                  <Picker.Item label="Cartao de credito" value="Cartao de credito" />
                  <Picker.Item label="Dinheiro" value="Dinheiro" />
                  <Picker.Item label="Cartao de debito" value="Cartao de debito" />
                  <Picker.Item label="Transferência" value="Transferência" />

                </Picker>
              </Item>
            </View>
          </View>
        </View>

        <View style={{ marginLeft: 21, marginTop: 20, paddingRight: 20 }}>
          <View style={{ marginBottom: 5 }}>
            <Text style={{ fontWeight: "bold", }}>Detalhes da conta</Text>
          </View>
          <View>
            <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
              <Text style={{ fontSize: 13, fontWeight: "bold", }}>Total dos itens: </Text>
              <Text style={{ fontSize: 13, fontWeight: "bold", }}>R$ {this.mascaraValor(eval(parseFloat(this.vl)).toFixed(2))}</Text>
            </View>
            <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
              <Text style={{ fontSize: 13, fontWeight: "bold", }}>Taxa de entrega: </Text>
              <Text style={{ fontSize: 13, fontWeight: "bold", }}>R$ {this.mascaraValor(eval(parseFloat(this.state.vlEntrega)).toFixed(2))}</Text>
            </View>
            <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
              <Text style={{ fontSize: 13, fontWeight: "bold", }}>Descontos: </Text>
              <Text style={{ fontSize: 13, fontWeight: "bold", }}>R$ {this.mascaraValor(eval(parseFloat(this.state.vlCupom)).toFixed(2))}</Text>
            </View>
            <View style={{ flexDirection: 'row', justifyContent: 'space-between' }}>
              <Text style={{ fontSize: 13, fontWeight: "bold", }}>Total: </Text>
              <Text style={{ fontSize: 13, fontWeight: "bold", }}>R$ {this.mascaraValor(eval((parseFloat(this.vl) + parseFloat(this.state.vlEntrega)) - parseFloat(this.state.vlCupom)).toFixed(2))}</Text>
            </View>
          </View>
        </View>

        <View style={{ marginLeft: 21, marginTop: 30, paddingRight: 18, marginBottom: 40 }}>
          <Button full success onPress={() => {
            if (this.state.ativo) {
              if (this.state.enderedoEnd !== '') {
                Alert.alert(
                  "Atenção",
                  "Deseja finalizar seu pedido?",
                  [
                    {
                      text: 'Não',
                      onPress: () => {

                      },
                      style: 'cancel',
                    },
                    {
                      text: 'Sim', onPress: async () => {
                        if (this.vl < this.state.vlMinimo) {
                          MAlert("Valor minimo do pedido e de R$ 15,00", 'Atenção', false)
                        } else {
                          this.setState({ isMoto: true })
                        }

                      }
                    },
                  ],
                  { cancelable: false },
                );

              } else {
                MAlert('Informe o endereço de entrega!', 'Atenção', false);
              }
            } else {
              MAlert('Estamos fechado', 'Atenção', false);
            }
          }}>
            <Text>Continuar</Text>
          </Button>
          {this.mdFinal()}
        </View>
      </SafeAreaView >
    );
  }

  mascaraValor(valor) {
    valor = valor.toString().replace(/\D/g, "");
    valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
    return valor
  }

  onValueChange(value) {
    this.setState({
      pagamento: value
    });
  }

  onValueChange2(value) {
    value === 'Sim' ? this.setState({ obs: '' }) : null
    this.setState({
      nf: value
    });
  }

  render() {
    return (Object.entries(this.props.carr.carrinho).length > 0 ? (this.state.isLoading ? <Carregando /> : this.form()) : this.vazio());
  }

  vazio() {
    return (
      <Container>
        <Content>
          <View style={{ flexDirection: 'row', justifyContent: 'space-between', paddingTop: 5, paddingLeft: 5, paddingRight: 15, }}>
            <View>
              <Text style={{ fontSize: 28, fontWeight: "bold", marginLeft: 15, marginTop: 15 }}>Carrinho</Text>
            </View>
          </View>
          <View style={{ marginTop: 100, alignItems: 'center' }}>
            <Icon style={{ fontSize: 140, color: '#d9dada', marginLeft: -32 }} type='Feather' name='shopping-cart' />
            <Text style={{ marginTop: 20 }}>Carrinho vazio</Text>
          </View>
        </Content>
      </Container>
    );
  }

  mdFinal() {
    return (
      <Modal
        animationType="fade"
        transparent={true}
        visible={this.state.isMoto}
        onRequestClose={() => {
          this.setState({ isMoto: false })
        }}>
        <View style={{ flex: 1, marginTop: 0, backgroundColor: 'rgba(0, 0, 0, 0.2)', }}>
          <View style={{ flex: 1, marginTop: 100, backgroundColor: '#ffffff', borderTopRightRadius: 0, borderTopLeftRadius: 0 }}>
            <View style={{ marginTop: 10, marginLeft: 25 }}>
              <TouchableOpacity style={{ height: 40, width: 40 }} onPress={() => { this.setState({ isMoto: false }) }}>
                <Icon name="close" />
              </TouchableOpacity>
            </View>
            <ScrollView>
              <View><Text style={{ fontSize: 12, fontWeight: 'bold', marginLeft: 15, marginBottom: 15 }}>Informações finais</Text></View>

              {/* <ListItem>
                <CheckBox checked={this.state.isAgend} onPress={() => { this.setState({ isAgend: !this.state.isAgend }) }} />
                <Body>
                  <Text>Agendar entrega?</Text>
                </Body>
              </ListItem> */}
              <View style={{ width: '100%', marginTop: 15, paddingLeft: 15, paddingRight: 15 }}>
                {this.state.isAgend ? <View>
                  <View style={{ marginTop: 5, width: '100%', marginLeft: 10 }}>
                    <DatePicker
                      style={{ width: 200 }}
                      date={this.state.date}
                      format="DD/MM/YYYY"
                      minDate={new Date().getDay() + "/" + new Date().getMonth() + "/" + new Date().getFullYear()}
                      maxDate="31-08-2025"
                      onDateChange={this.selectDate}
                      style={{ width: '100%' }}
                    />
                  </View>


                  <View style={{ marginTop: 10, width: '100%', marginLeft: 10, flexDirection: 'row' }}>
                    <Text style={{
                      width: '87%',
                      textAlign: 'center',
                      padding: 10,
                      borderColor: '#cccccc',
                      borderWidth: 1.3
                    }} onPress={() => this.TimePicker.open()}>{this.state.time}</Text>
                    <TimePicker
                      ref={ref => {
                        this.TimePicker = ref;
                      }}
                      textCancel="Cancelar"
                      textConfirm="Gravar"
                      selectedHour={new Date().getHours() + ""}
                      selectedMinute={new Date().getMinutes() + ""}
                      onCancel={() => this.onCancel()}
                      onConfirm={(hour, minute) => this.onConfirm(hour, minute)}
                    />
                    <TouchableOpacity style={{ width: 30, marginLeft: 10, marginTop: 5 }} iconLeft onPress={() => this.TimePicker.open()}>
                      <Icon type="Ionicons" name="md-time" style={{ color: "#000" }} />
                    </TouchableOpacity>

                  </View>
                </View> : null}

              </View>

              {this.state.pagamento === 'Dinheiro' ?
                <ListItem>
                  <CheckBox checked={this.state.isTroco} onPress={() => { this.setState({ isTroco: !this.state.isTroco }) }} />
                  <Body>
                    <Text>Deseja troco?</Text>
                  </Body>
                </ListItem> : null
              }

              <View style={{ width: '100%', marginTop: 15, paddingLeft: 15, paddingRight: 15 }}>
                {this.state.isTroco && this.state.pagamento === 'Dinheiro' ? <View>
                  <View style={{ marginTop: 20, width: '100%' }}>
                    <View style={{ marginBottom: 0, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' }}>
                      <Text style={{ fontWeight: "bold", }}>Valor para troco: </Text>
                    </View>
                    <View>
                      <View style={{ marginTop: 15, }}>
                        <Item>
                          <Input
                            value={this.state.vlTroco}
                            onChangeText={vlTroco => this.setState({ vlTroco })}
                          />
                        </Item>
                      </View>
                    </View>
                  </View>
                </View> : null}
              </View>

              {/* <ListItem>
                <CheckBox checked={this.state.isFisc} onPress={() => { this.setState({ isFisc: !this.state.isFisc }) }} />
                <Body>
                  <Text>Recibo?</Text>
                </Body>
              </ListItem> */}
              <View style={{ width: '100%', marginTop: 15, paddingLeft: 15, paddingRight: 15 }}>
                {this.state.isFisc ? <View>
                  <View style={{ marginTop: 20, width: '100%' }}>
                    <View style={{ marginBottom: 0, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' }}>
                      <Text style={{ fontWeight: "bold", }}>Informações para recibo: </Text>
                    </View>
                    <View>
                      <View style={{ marginTop: 15, }}>
                        <Item>
                          <Input
                            value={this.state.notaFiscal}
                            onChangeText={notaFiscal => this.setState({ notaFiscal })}
                          />
                        </Item>
                      </View>
                    </View>
                  </View>
                </View> : null}
              </View>

              <View style={{ width: '100%', alignItems: 'center' }}>
                <View style={{ width: '100%', marginTop: 15, paddingLeft: 15, paddingRight: 15 }}>
                  <View style={{ marginBottom: 0, flexDirection: 'row', justifyContent: 'space-between', alignItems: 'center' }}>

                  </View>


                  <View style={{ marginTop: 20, }}>
                    <Button full success onPress={() => {
                      if (this.state.pagamento === 'Dinheiro' && this.state.vlTroco === '') {
                        MAlert('Informe um valor para o troco', 'Atenção', false);
                      } else {
                        this.handlleEnviarPedido()
                      }

                    }}>
                      <Text>Finalizar pedido</Text>
                    </Button>
                  </View>
                </View>
              </View>
            </ScrollView>
          </View>
        </View>
      </Modal >
    );
  }

  onCancel() {
    this.TimePicker.close();
  }

  onConfirm(hour, minute) {
    this.setState({ time: `${hour}:${minute}`, obs: " Entregar as: " + `${hour}:${minute}` });
    this.TimePicker.close();
  }

  selectDate = (date) => {
    this.setState({ date: date });
  }
}

const mapStateToProps = state => ({
  carr: state.CarrinhoReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setCar, setCarEmp }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Carrinho);
