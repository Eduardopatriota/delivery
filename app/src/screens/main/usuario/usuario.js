import React, { Component } from 'react';
import { Container, ListItem, Left, Body, Right, Icon, Text, Content, Badge, Button, Footer, FooterTab } from 'native-base';
import { View, TouchableOpacity, Alert } from 'react-native';
import SkeletonPlaceholder from "react-native-skeleton-placeholder";
import { MAlert } from './../../../utilites/utilites';
import { openDatabase } from 'react-native-sqlite-storage';
import api from './../../../utilites/api';
import { StackActions, NavigationActions } from 'react-navigation';
import { setCar, getCar, setCarEmp, setCurrentTab, setPedidoA } from './../../../redux/actions';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
var db = openDatabase({ name: 'delivery.db', createFromLocation: 1 });


const logoutAction = StackActions.reset({
  index: 0,
  actions: [NavigationActions.navigate({ routeName: 'Splash' })],
});

class Usuario extends Component {

  state = {
    user: [],
    nome: '',
    celular: '',
    idUser: '',
    endPadrao: '',
    auth_token: '',
    isLoading: false

  }

  async componentDidMount() {
    this.setState({ isLoading: true });

    const { setCurrentTab } = this.props;

    db.transaction(tx => {
      tx.executeSql('SELECT * FROM user', [], (tx, results) => {
        console.log('item: ', results.rows.item(0));
        if (results.rows.length > 0) {
          this.setState({ idUser: results.rows.item(0).id, nome: results.rows.item(0).nome, celular: results.rows.item(0).telefone });
        } else {
          setCurrentTab(0);
        }
      });
    });
  }


  form() {
    const { setCurrentTab } = this.props;
    return (
      <Container>
        <Content>
          <View style={{ marginTop: 15, marginLeft: 14, marginRight: 15, }}>

            <Text style={{ fontSize: 23, fontWeight: "bold", }}>{this.state.nome}</Text>
            <Text style={{ marginTop: 5, }}>{this.state.celular}</Text>
            <View style={{ width: '100%', borderWidth: 2, borderColor: '#e6e6e6', borderWidth: 1, marginTop: 25, marginBottom: 30 }}></View>

            <ListItem icon style={{ marginLeft: 5 }} onPress={() => {
              this.props.navigation.navigate('Endereco');
            }}>
              <Left>
                <Icon style={{ fontSize: 20 }} type='AntDesign' name="home" />
              </Left>
              <Body>
                <Text style={{ fontSize: 15 }}>Meus endereços</Text>
              </Body>
              <Right>
              </Right>
            </ListItem>

            <ListItem icon style={{ marginLeft: 5, marginTop: 15 }} onPress={() => {              

              this.props.navigation.navigate('Pedidos');
            }}>
              <Left style={{ alignItems: 'center' }}>
                <Icon style={{ fontSize: 20 }} name="shoppingcart" type="AntDesign" />
              </Left>
              <Body>
                <Text style={{ fontSize: 15 }}>Meus pedidos</Text>                
              </Body>
              <Right>
              {this.props.pedidoA.pedidoA === '1' ? <Badge><Text style={{color: 'red'}}>  </Text></Badge> : null}
              </Right>
            </ListItem>

            <ListItem icon style={{ marginLeft: 5, marginTop: 35 }} onPress={() => {
              Alert.alert(
                "Atanção",
                "Deseja fazer o logoff so aplicativo?",
                [
                  {
                    text: 'Não',
                    onPress: () => {
                    },
                    style: 'cancel',
                  },
                  {
                    text: 'Sim', onPress: async () => {
                      setCurrentTab(0);
                      db.transaction(tx => {
                        tx.executeSql('DELETE FROM user', [], (tx, results) => {
                          this.props.navigation.dispatch(logoutAction);
                        });
                      });
                    }
                  },
                ],
                { cancelable: false },
              );
            }}>
              <Left>
                <Icon style={{ fontSize: 15, color: 'red' }} name="poweroff" type="AntDesign" />
              </Left>
              <Body>
                <Text style={{ color: 'red', fontSize: 15 }} >Fazer logoff</Text>
              </Body>
              <Right>
              </Right>
            </ListItem>
          </View>
        </Content>
      </Container>
    );
  }

  enderecoS() {
    return (
      <Container>
        <Content>
          {this.head()}
          <View style={{ marginTop: 10, marginLeft: 15, marginRight: 15 }}>
            <SkeletonPlaceholder>
              <View style={{ width: "100%", height: 100, marginBottom: 5 }} />
            </SkeletonPlaceholder>
          </View>
        </Content>
      </Container>
    );
  }

  head() {
    return (
      <View style={{ width: '100%', height: 65, backgroundColor: '#fff', flexDirection: 'row' }}>
        <Text style={{ fontSize: 23, fontWeight: "bold", marginLeft: 15, marginTop: 16 }}>Usuário</Text>
      </View>
    );
  }

  render() {
    return this.form();
  }
}

const mapStateToProps = store => ({
  carrinho: store.CarrinhoReducer,
  pedidoA: store.PedidoAReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setCar, getCar, setCarEmp, setCurrentTab, setPedidoA }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Usuario);