import React, { Component } from 'react';
import { Container, Label, Input, Spinner, Item, Icon, Text, Content, Button, Footer, FooterTab, Picker } from 'native-base';
import { View, TouchableOpacity, StatusBar } from 'react-native';
var db = openDatabase({ name: 'delivery.db', createFromLocation: 1 });
import Valid from './validarEndereco';
import { MAlert } from './../../utilites/utilites';
import { openDatabase } from 'react-native-sqlite-storage';
import api from './../../utilites/api';
import { StackActions, NavigationActions } from 'react-navigation';

const logoutAction = StackActions.reset({
  index: 0,
  actions: [NavigationActions.navigate({ routeName: 'Main' })],
});

export default class CadEndereco extends Component {

  state = {
    titulo: '',
    end: '',
    colorHeader: Platform.OS === 'android' ? 'red' : "white",
    numero: '',
    bairro: '',
    cep: '',
    obs: '',
    bairros: [],

    idUser: '',
    auth_token: '',
    isLoading: false,
    default_address_id: ''
  }

  async componentDidMount() {
    this.setState({ isLoading: true });

    db.transaction(tx => {
      tx.executeSql('SELECT * FROM user', [], (tx, results) => {
        console.log('item: ', results.rows.item(0));
        if (results.rows.length > 0) {
          this.setState({ idUser: results.rows.item(0).id, auth_token: results.rows.item(0).auth_token, endPadrao: results.rows.item(0).default_address_id });
        } else {
        }
      });
    });
    this.getCoordinates();
    this.setState({ isLoading: false });
  }

  handleSubimit = async () => {
    console.log('Resposta: ', 'response.data')
    if (Valid(this.state)) {

      this.setState({ isLoading: true });
      const data = new FormData();
      data.append('nome', this.state.titulo);
      data.append('rua', this.state.end);
      data.append('numero', this.state.numero);
      data.append('bairro', this.state.bairro);
      data.append('complemento', this.state.obs);
      data.append('idUser', this.state.idUser);

      const response = await api.post('ws/CadEndereco.php', data);
      if (response.status === 200) {
        console.log('Resposta: ', response.data)
        if (response.data.valor > 0) {
          db.transaction(tx => {
            tx.executeSql(
              'DELETE FROM endereco', null,
              (tx, results) => {
                this.preecherTab();
              }
            );
          });
        }
      } else {
        MAlert('Erro na comunicação com servidor!', 'Atenção', false);
        this.setState({ isLoading: false });
      }

    } else {
      MAlert('Informe todos os dados marcados com ( * )', 'Atenção', false);
      this.setState({ isLoading: false });
    }
  }

  async getCoordinates() {


    try {
      const response = await api.post('ws/ListBairros.php');
      console.log('resposta: ', response.data)
      if (response.status === 200) {
        var tem = [];
        tem = response.data;
        tem.unshift({ "id": "0", "nome": "Selecione seu bairro", "valor": "2" });
        if (response.data !== '[]') {
          this.setState({
            bairros: tem
          });
        }
      } else {
        MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
      }

    } catch (error) {
      console.log('eroo ', error)
      MAlert('Erro na comunicação com servidor!', 'Atenção', false);
    }

  }

  onValueChange(value) {
    this.setState({
      bairro: value
    });
  }

  async preecherTab() {
    console.log('Resposta: ', 'dsafdsçlkf')
    db.transaction(tx => {
      console.log('dsfds');
      tx.executeSql(
        'INSERT INTO endereco (id, titulo, end, numero, bairro, obs, preco) VALUES (?,?,?,?,?,?,?)',
        [null, this.state.titulo, this.state.end, this.state.numero, this.state.bairro, this.state.obs, null],
        (tx, results) => {
          console.log('Results', results);
          if (results.rowsAffected > 0) {
            this.props.navigation.dispatch(logoutAction);
          } else {
            this.setState({ isLoading: false })
            alert('Falha ao inserir usuário no banco de dados');
          }
        }
      );
    });
  }

  form() {
    return (
      <Container>
        <Content>
          {this.header()}
          <View style={{ marginTop: 10, marginLeft: 18, marginRight: 25 }}>

            <View style={{ marginTop: 25, justifyContent: 'space-between' }}>
              <Label style={{ fontSize: 13, color: 'red' }}>Atenção: Verifique se atendemos o seu bairro</Label>
            </View>

            <Item stackedLabel style={{ marginTop: 25, justifyContent: 'space-between' }}>
              <Label style={{ fontSize: 13 }}>Bairro *</Label>
              <View
                style={{ width: '100%' }}>
                <Picker
                  mode="dropdown"
                  iosHeader="Selecione o bairro"
                  iosIcon={<Icon name="arrow-down" />}
                  selectedValue={this.state.bairro}
                  onValueChange={this.onValueChange.bind(this)}
                >
                  {this.state.bairros.map(i => (
                    <Picker.Item label={i.nome} value={i.nome} />
                  ))}
                </Picker>
              </View>
            </Item>

            {this.state.bairro !== '' && this.state.bairro !== 'Selecione seu bairro' ? this.restoInf() : null}

          </View>
        </Content>
        <Footer>
          <FooterTab style={{ backgroundColor: 'green' }}>
            {this.state.isLoading ?
              <View style={{ width: '100%' }}>
                <Spinner color='white' style={{ marginTop: -15 }} />
              </View> :
              <Button full onPress={this.handleSubimit}>
                <Text style={{ color: 'white' }}>Cadastrar</Text>
              </Button>}
          </FooterTab>
        </Footer>
      </Container>
    );
  }

  restoInf() {
    return (
      <View>
        <Item stackedLabel style={{ marginTop: 25 }}>
          <Label style={{ fontSize: 13 }}>Nome (ex: Minha casa, meu trabalho, casa da mãe) *</Label>
          <Input
            ref='Field1'
            returnKeyType='next'
            value={this.state.titulo}
            autoFocus={true}
            onSubmitEditing={() => { this.refs.Field2._root.focus(); }}
            onChangeText={titulo => this.setState({ titulo })}
            editable={!this.state.isLoading}
          />
        </Item>

        <Item stackedLabel style={{ marginTop: 25 }}>
          <Label style={{ fontSize: 13 }}>Seu endereço (Rua) *</Label>
          <Input
            ref='Field2'
            onSubmitEditing={() => { this.refs.Field3._root.focus(); }}
            returnKeyType='next'
            editable={!this.state.isLoading}
            value={this.state.end}
            useNativeDriver={true}
            onChangeText={end => this.setState({ end })}
          />
        </Item>

        <Item stackedLabel style={{ marginTop: 25 }}>
          <Label style={{ fontSize: 13 }}>Numero *</Label>
          <Input
            ref='Field3'
            onSubmitEditing={() => { this.refs.Field4._root.focus(); }}
            returnKeyType='next'
            editable={!this.state.isLoading}
            value={this.state.numero}
            useNativeDriver={true}
            onChangeText={numero => this.setState({ numero })}
          />
        </Item>

        <Item stackedLabel style={{ marginTop: 25 }}>
          <Label style={{ fontSize: 13 }}>CEP</Label>
          <Input
            ref='Field4'
            editable={!this.state.isLoading}
            value={this.state.cep}
            useNativeDriver={true}
            onChangeText={cep => this.setState({ cep })}
          />
        </Item>

        <Item stackedLabel style={{ marginTop: 25 }}>
          <Label style={{ fontSize: 13 }}>Obs</Label>
          <Input
            ref='Field6'
            editable={!this.state.isLoading}
            value={this.state.obs}
            useNativeDriver={true}
            onChangeText={obs => this.setState({ obs })}
          />
        </Item>
      </View>
    )
  }

  header() {
    return (
      <View style={{ flexDirection: 'row', justifyContent: 'space-between', backgroundColor: this.state.colorHeader, height: 60 }}>
        <View style={{ marginLeft: 15, marginTop: 5, flexDirection: 'row' }}>
          <TouchableOpacity style={{ height: 48, width: 30, flexDirection: 'row', alignItems: 'center' }} onPress={() => {
            this.props.navigation.goBack()
          }}>
            <Icon type="AntDesign" name="arrowleft" style={{color: Platform.OS === 'android' ? 'white' : "black" }} />
          </TouchableOpacity>
          <View style={{ height: 48, flexDirection: 'row', alignItems: 'center', marginTop: -2, marginLeft: 10 }}>
            <Text style={{ fontSize: 28, fontWeight: "bold", color: Platform.OS === 'android' ? 'white' : "black" }}>Endereços</Text>
          </View>
        </View>
      </View>
    );
  }

  render() {
    return this.form();
  }
}