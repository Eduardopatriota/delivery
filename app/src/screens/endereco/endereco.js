import React, { Component } from 'react';
import { Container, Label, Input, Spinner, TabHeading, Icon, Text, Content, Item, Button, Footer, FooterTab } from 'native-base';
import { View, TouchableOpacity, StatusBar } from 'react-native';
import SkeletonPlaceholder from "react-native-skeleton-placeholder";
var db = openDatabase({ name: 'delivery.db', createFromLocation: 1 });
import { MAlert } from './../../utilites/utilites';
import { openDatabase } from 'react-native-sqlite-storage';
import api from './../../utilites/api';
import { StackActions, NavigationActions } from 'react-navigation';
import ListEnderecos from './../fragments/listEndereco';

const logoutAction = StackActions.reset({
  index: 0,
  actions: [NavigationActions.navigate({ routeName: 'Main' })],
});

export default class Endereco extends Component {

  state = {
    enderecos: [],
    colorHeader: Platform.OS === 'android' ? 'red' : "white",
    idUser: '',
    isLoading: false,
    endPadrao: ''

  }

  async componentDidMount() {
    this.setState({ isLoading: true });

    db.transaction(tx => {
      tx.executeSql('SELECT * FROM endereco', [], (tx, results) => {
        console.log('item: ', results.rows.item(0));
        if (results.rows.length > 0) {
          this.setState({ endereco: results.rows.item(0) });
          this.setState({
            endPadrao: results.rows.item(0).id,
          });
        } else {
        }
      });
    });

    db.transaction(tx => {
      tx.executeSql('SELECT * FROM user', [], (tx, results) => {
        if (results.rows.length > 0) {
          this.setState({ idUser: results.rows.item(0).id });
          this.getServidor();
        }
      });
    });
  }

  async getServidor() {
    try {
      const data = new FormData();
      data.append('id_user', this.state.idUser);

      const response = await api.post('ws/ListEndereco.php', data);
      console.log("resposta ", response.data)
      if (response.status === 200) {
        this.setState({
          enderecos: response.data
        });
        this.setState({
          
        });
      } else {
        MAlert('Erro na comunicação com  servidor!', 'Atenção', false);
      }

    } catch (error) {
      console.log('Erro ', error)
      MAlert('Erro na comunicação com servidor!', 'Atenção', false);
    }

    this.setState({ isLoading: false });
  }

  async preecherTab(i) {

    db.transaction(tx => {
      console.log('dsfds');
      tx.executeSql(
        'INSERT INTO endereco (id, titulo, end, numero, bairro, obs, preco) VALUES (?,?,?,?,?,?,?)',
        [i.id, i.Nome, i.Rua, i.Numero, i.Bairro, i.Complemento, i.Valor],
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
          <View style={{ marginTop: 10, marginLeft: 15, marginRight: 15 }}>
            {this.state.enderecos.map(i => (
              <ListEnderecos Nome={i.Nome} Endereco={i.Rua} idDefault={this.state.endPadrao === i.id ? true : false}
                Press={async () => {
                  this.setState({ isLoading: true });
                  db.transaction(tx => {
                    tx.executeSql(
                      'DELETE FROM endereco', null,
                      (tx, results) => {
                        this.preecherTab(i);
                      }
                    );
                  });
                }}
              />
            ))}
          </View>
        </Content>
        <Footer>
          <FooterTab style={{ backgroundColor: 'green' }}>
            <Button full onPress={() => {
              this.props.navigation.navigate('CadEndereco', {
                quemChamou: 'List'
              });
            }}>
              <Text style={{ color: 'white' }}>Novo endereço</Text>
            </Button>
          </FooterTab>
        </Footer>
      </Container>
    );
  }

  enderecoS() {
    return (
      <Container>
        <Content>
          {this.header()}
          <View style={{ marginTop: 10, marginLeft: 15, marginRight: 15 }}>
            <SkeletonPlaceholder>
              <View style={{ width: "100%", height: 100, marginBottom: 5 }} />
              <View style={{ width: "100%", height: 100, marginBottom: 5 }} />
            </SkeletonPlaceholder>
          </View>
        </Content>
      </Container>
    );
  }

  header() {
    return (
      <View style={{ flexDirection: 'row', justifyContent: 'space-between', backgroundColor: this.state.colorHeader, height: 60 }}>
        <View style={{ marginLeft: 15, marginTop: 5, flexDirection: 'row' }}>
          <TouchableOpacity style={{ height: 48, width: 30, flexDirection: 'row', alignItems: 'center' }} onPress={() => {
            this.props.navigation.goBack()
          }}>
            <Icon type="AntDesign" name="arrowleft" style={{ color: Platform.OS === 'android' ? 'white' : "black" }} />
          </TouchableOpacity>
          <View style={{ height: 48, flexDirection: 'row', alignItems: 'center', marginTop: -2, marginLeft: 10 }}>
            <Text style={{ fontSize: 28, fontWeight: "bold", color: Platform.OS === 'android' ? 'white' : "black" }}>Endereços</Text>
          </View>
        </View>
      </View>
    );
  }

  render() {
    return this.state.isLoading ? this.enderecoS() : this.form();
  }
}