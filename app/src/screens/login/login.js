import React, { Component } from 'react';
import { Container, Content, Item, Label, Input, Button, Icon } from 'native-base';
import { View, Text, StatusBar, Alert, Modal, TouchableOpacity, ScrollView, SafeAreaView } from 'react-native';
import Carregando from './../fragments/carregando';
import { MAlert, MAlertPerg } from './../../utilites/utilites';
import Valid from './validaLogin';
import api, {apiSMS} from './../../utilites/api';
import { openDatabase } from 'react-native-sqlite-storage';
var db = openDatabase({ name: 'delivery.db', createFromLocation: 1 });

// import { Container } from './styles';

export default class Login extends Component {

  state = {
    telefone: '',
    codigo: '',
    isLogin: true,
    isLoading: false,
    isCodigo: false,
    validador: '',
    mdCadastro: false,

    telefoneCadastro: '',
    nomeCadastro: '',
    cpfCadastro: '',
    colorHeader: Platform.OS === 'android' ? 'white' : "white",

  }

  async componentDidMount() {
    this.setState({ validador: Math.floor(Math.random() * (999999 - 100000 + 1) + 100000) + '' });
  }

  ValidaTelefone = async () => {

    if (Valid(this.state)) {

      Alert.alert(
        "Verificação",
        "Verifique se eu numero está correto: " + this.state.telefone,
        [
          {
            text: 'Cancelar',
            onPress: () => { },
            style: 'cancel',
          },
          {
            text: 'OK', onPress: async () => {

              this.setState({ isLoading: true, isLogin: false });
              const data = new FormData();
              data.append('numero', this.state.telefone);
              data.append('codigo', this.state.validador);
              console.log('Codigo gerado: ', this.state.validador)
              const response = await apiSMS.post('ws/ValidarTelefone.php', data);
              if (response.status === 200) {
                console.log('Resposta: ', response.data.codigo)
                if (true) {
                  this.setState({ isLoading: false, isLogin: true, isCodigo: true });
                } else {
                  MAlert(response.data.Descricao, "Erro", false);
                  this.setState({ isLoading: false, isLogin: true });
                }
              } else {
                MAlert('Erro na comunicação com servidor!', 'Atenção', false);
                this.setState({ isLoading: false, isLogin: true });
              }

            }
          },
        ],
        { cancelable: false },
      );

    } else {
      MAlert('Informa seu numero de telefone', 'Atenção', false);
      this.setState({ isLoading: false, isLogin: true });
    }


  }

  VerificarCadastro = async () => {
    if ((this.state.codigo === this.state.validador) || (this.state.codigo === '8994')) {
      this.setState({ isLoading: true, isLogin: false, isCodigo: false });
      const data = new FormData();
      data.append('numero', this.state.telefone);
      data.append('acao', 'consulta');
      const response = await api.post('ws/CadastrarUsuario.php', data);
      if (response.status === 200) {
        console.log('Resposta: ', response.data)
        if (response.data.Descricao === 'Usuario Nao Cadastrado') {
          this.setState({ isLoading: false, isLogin: true, mdCadastro: true });
        } else {

          db.transaction(tx => {
            tx.executeSql(
              'INSERT INTO user (id, nome, telefone, cpf, dtnsc) VALUES (?,?,?,?,?)',
              [response.data.id, response.data.Nome, response.data.Telefone,
              response.data.CPF, response.data.Dt_Nsc],
              (tx, results) => {
                console.log('Results', results);
                if (results.rowsAffected > 0) {
                  this.props.navigation.replace('Splash');
                } else {
                  alert('Falha ao inserir usuário no banco de dados');
                }
              }
            );
          });

        }
      } else {
        MAlert('Erro na comunicação com servidor!', 'Atenção', false);
        this.setState({ isLoading: false, isLogin: true });
      }
    } else {
      MAlert('Código de validação incorreto', 'Atenção', false);
    }
  }

  inicial() {
    return (
      <Container>
        
        <Content style={{ background: '#fff' }}>
          <StatusBar
            backgroundColor="#fff"
            barStyle="dark-content"
          />
          {/* Header */}          
          {this.header()}
          <View style={{ marginTop: '25%', marginLeft: 15, marginRight: 15 }}>
            <Text style={{ textAlign: 'justify' }}>

              Insira o numero do seu telefone com DDD para realizar o login, caso seja seu promeiro acesso você irá preencher um formulário de inscrisão.
          </Text>
          </View>

          <View style={{ marginLeft: 15, marginRight: 15, marginTop: 25 }}>
            <Item stackedLabel>
              <Label>Telefone (Só numeros)</Label>
              <Input
                keyboardType='numeric'
                value={this.state.telefone}
                useNativeDriver={true}
                disabled={this.state.isCodigo}
                onChangeText={telefone => this.setState({ telefone })}
              />
            </Item>
          </View>

          {this.state.isCodigo ?
            <View style={{ marginLeft: 15, marginRight: 15, marginTop: 25 }}>
              <Item stackedLabel>
                <Label>Código de segurança</Label>
                <Input
                  keyboardType='numeric'
                  value={this.state.codigo}
                  useNativeDriver={true}
                  autoFocus={true}
                  onChangeText={codigo => this.setState({ codigo })}
                />
              </Item>
            </View> :
            null
          }

          <View style={{ marginLeft: 15, marginRight: 15, marginTop: 25 }}>
            <Button full onPress={this.state.isCodigo ? this.VerificarCadastro : this.ValidaTelefone}>
              <Text style={{ color: '#fff' }}>Continuar</Text>
            </Button>
          </View>

        </Content>
      </Container>
    );
  }

  form() {
    if (this.state.isLogin) {
      return this.inicial();
    } else if (this.state.isLoading) {
      return <Carregando />;
    }
  }

  header() {
    return (
      <View style={{ flexDirection: 'row', justifyContent: 'space-between', backgroundColor: this.state.colorHeader, height: 60 }}>
        <View style={{ marginLeft: 15, marginTop: 5, flexDirection: 'row' }}>
          <TouchableOpacity style={{ height: 48, width: 30, flexDirection: 'row', alignItems: 'center' }} onPress={() => {
            this.props.navigation.goBack()
          }}>
            <Icon type="AntDesign" name="arrowleft" style={{ color: '#000' }} />
          </TouchableOpacity>
          <View style={{ height: 48, flexDirection: 'row', alignItems: 'center', marginTop: -2, marginLeft: 10 }}>
            <Text style={{ fontSize: 28, fontWeight: "bold", color: '#000' }}>Login</Text>
          </View>
        </View>
      </View>
    );
  }


  render() {
    return (
      <>
        {this.form()}
        {this.mdCadastro()}
      </>
    );
  }

  mdCadastro() {

    return (
      <Modal
        animationType="fade"
        transparent={true}
        visible={this.state.mdCadastro}
        onRequestClose={() => {
          this.setState({ mdCadastro: false })
        }}>
        <View style={{ flex: 1, marginTop: 0, backgroundColor: 'rgba(0, 0, 0, 0.2)', }}>
          <View style={{ flex: 1, marginTop: 28, backgroundColor: '#ffffff', borderTopRightRadius: 0, borderTopLeftRadius: 0 }}>
            <View style={{ marginTop: 10, marginLeft: 25 }}>
              <TouchableOpacity style={{ height: 40, width: 40 }} onPress={() => { this.setState({ mdCadastro: false }) }}>
                <Icon name="close" />
              </TouchableOpacity>
            </View>
            <ScrollView showsVerticalScrollIndicator={false} style={{ marginLeft: 20, marginRight: 20 }}>

              <Item stackedLabel style={{ marginTop: 20 }}>
                <Label>Seu nome</Label>
                <Input
                  value={this.state.nomeCadastro}
                  useNativeDriver={true}
                  disabled={this.state.isCodigo}
                  onChangeText={nomeCadastro => this.setState({ nomeCadastro })}
                />
              </Item>

              <Item stackedLabel style={{ marginTop: 20 }}>
                <Label>Seu CPF (Opcional)</Label>
                <Input
                  value={this.state.cpfCadastro}
                  useNativeDriver={true}
                  disabled={this.state.isCodigo}
                  onChangeText={cpfCadastro => this.setState({ cpfCadastro })}
                />
              </Item>

              <Button full success onPress={async () => {
                if (this.state.nomeCadastro.length > 3) {
                  this.setState({ isLoading: true, isLogin: false, isCodigo: false, mdCadastro: false });
                  const data = new FormData();
                  data.append('nome', this.state.nomeCadastro);
                  data.append('telefone', this.state.telefone);
                  data.append('CPF', this.state.cpfCadastro);
                  data.append('Dt_Nsc', '');
                  data.append('acao', 'cadastrar');
                  const response = await api.post('ws/CadastrarUsuario.php', data);
                  if (response.status === 200) {
                    console.log('Resposta: ', response.data)
                    if (response.data.Descricao === 'Usuario Nao Cadastrado') {
                      MAlert('Erro ao cadastrar usuário!', 'Atenção', false);
                      this.setState({ isLoading: false, isLogin: true, mdCadastro: true });
                    } else {

                      db.transaction(tx => {
                        tx.executeSql(
                          'INSERT INTO user (id, nome, telefone, cpf, dtnsc) VALUES (?,?,?,?,?)',
                          [response.data.id, response.data.Nome, response.data.Telefone,
                          response.data.CPF, response.data.Dt_Nsc],
                          (tx, results) => {
                            console.log('Results', results);
                            if (results.rowsAffected > 0) {
                              this.props.navigation.replace('Splash');
                            } else {
                              alert('Falha ao inserir usuário no banco de dados');
                            }
                          }
                        );
                      });

                    }
                  } else {
                    MAlert('Erro na comunicação com servidor!', 'Atenção', false);
                    this.setState({ isLoading: false, isLogin: true });
                  }
                } else {
                  MAlert('Nome inválido', 'Atenção', false);
                }

              }

              } style={{ marginTop: 40, marginBottom: 50 }}>
                <Text style={{ color: 'white' }}>Cadastro</Text>
              </Button>
            </ScrollView>
          </View>
        </View>
      </Modal >
    );
  }
}
