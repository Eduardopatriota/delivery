import React, { Component } from 'react';
import { Container, Label, Input, Spinner, TabHeading, Icon, Text, Content, Item, Button, Footer, FooterTab } from 'native-base';
import { View, TouchableOpacity, StatusBar } from 'react-native';
import SkeletonPlaceholder from "react-native-skeleton-placeholder";
var db = openDatabase({ name: 'delivery.db', createFromLocation: 1 });
import { MAlert } from './../../utilites/utilites';
import { openDatabase } from 'react-native-sqlite-storage';
import api from './../../utilites/api';
import { setPedidoA } from './../../redux/actions';
import { connect } from 'react-redux';
import { bindActionCreators } from 'redux';
import { StackActions, NavigationActions } from 'react-navigation';
import LisPedidos from './../fragments/listPedidos'

class Pedidos extends Component {
  state = {
    pedidos: [],
    idUser: '',
    nomeUser: '',
    isLoading: false,
    colorHeader: Platform.OS === 'android' ? 'red' : "white",
    endPadrao: ''
  }

  async componentDidMount() {
    this.setState({ isLoading: true });
    db.transaction(tx => {
      tx.executeSql('SELECT * FROM user', [], (tx, results) => {
        if (results.rows.length > 0) {
          this.setState({ idUser: results.rows.item(0).id, nomeUser: results.rows.item(0).nome });
          this.getServidor();
        }
      });
    });
  }

  async getServidor() {
    try {

      const {
        setPedidoA
      } = this.props;



      const data = new FormData();
      data.append('cliente', this.state.idUser + '-' + this.state.nomeUser);

      const response = await api.post('ws/list_pedidos.php', data);
      const response2 = await api.post('ws/PedidoAberto1.php', data);
      console.log("resposta ", response.data);
      console.log("resposta ", response2.data);

      setPedidoA("0");

      if (response.status === 200) {
        this.setState({
          enderecos: response.data
        });
        this.setState({
          pedidos: response.data
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

  form() {
    return (
      <Container>
        <StatusBar
            backgroundColor={Platform.OS === 'android' ? "red" : "white"}
            barStyle={Platform.OS === 'android' ? "ligth-content" : "dark-content"}
          />
        <Content>
          {this.header()}
          <View style={{ marginTop: 10, marginLeft: 15, marginRight: 15 }}>
            {this.state.pedidos.map(i => (
              <LisPedidos Data={i.Data} Valor={i.Valor} Status={i.Status} Itens={i.Itens} />
            ))}
          </View>
        </Content>
      </Container>
    );
  }

  enderecoS() {
    return (
      <Container>
        <Content>
          <StatusBar
            backgroundColor={Platform.OS === 'android' ? "red" : "white"}
            barStyle={Platform.OS === 'android' ? "ligth-content" : "dark-content"}
          />
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
            <Icon type="AntDesign" name="arrowleft" style={{ color: '#fff' }} />
          </TouchableOpacity>
          <View style={{ height: 48, flexDirection: 'row', alignItems: 'center', marginTop: -2, marginLeft: 10 }}>
            <Text style={{ fontSize: 28, fontWeight: "bold", color: '#fff' }}>Meus pedidos</Text>
          </View>
        </View>
      </View>
    );
  }

  render() {
    return this.state.isLoading ? this.enderecoS() : this.form();
  }
}

const mapStateToProps = store => ({
  pedidoA: store.PedidoAReducer
});

const mapDispatchToProps = dispatch =>
  bindActionCreators({ setPedidoA }, dispatch);

export default connect(
  mapStateToProps,
  mapDispatchToProps
)(Pedidos);
