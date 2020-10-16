import React, { Component } from 'react';
import { Container, Header, Content, List, ListItem, Left, Body, Thumbnail } from 'native-base';
import { View, Text, SafeAreaView, Image, TouchableOpacity, ScrollView, ImageBackground } from 'react-native';
import VariaveisGlobais from './../../utilites/variaveisDoSistema';


// import { Container } from './styles';

export default class ListCardapio extends Component {


  render() {

    if (this.props.padrao === '1') {
      return this.listUm()
    } else if (this.props.padrao === '2') {
      return  this.listDois()
    } else {
      return  this.listTres()
    }

  }

  listDois() {
    const vglobais = new VariaveisGlobais();
    return (
      <ListItem avatar style={{ marginLeft: 0, marginRight: 15, }} onPress={() => {
        this.props.acao()
      }}>
        <Left>
          <Thumbnail source={{ uri: vglobais.getHostIMG + '/gp/' + this.props.Imagem }} style={{ width: 40, height: 40, marginRight: 0 }} />
        </Left>
        <Body>
          <Text style={{ paddingTop: 10, }}>{this.props.Nome}</Text>
          <Text style={{ color: 'white' }}>.</Text>
        </Body>
      </ListItem>
    );
  }

  listUm() {
    const vglobais = new VariaveisGlobais();
    const image = { uri: vglobais.getHostIMG + '/gp/'  + this.props.Imagem };
    return (
      <TouchableOpacity style={{
        width: '49%', height: 165,
        borderRadius: 20,
        borderWidth: 2,
        borderColor: '#fff',
        marginBottom: 10
      }} onPress={() => {
        this.props.acao()
      }}>
        <ImageBackground source={image} style={{ flex: 1, resizeMode: "cover", justifyContent: "center" }}>
          <View style={{
            width: '100%', flex: 1, backgroundColor: "rgba(0, 0, 0, 0.40)",
            borderRadius: 0,
            borderWidth: 0,
            borderColor: '#fff',
            flexDirection: 'row',
            alignItems: 'center',
          }}>
            <Text style={{ fontSize: 17, width: '100%', textAlign: 'center', fontWeight: 'bold', color: '#fff' }}>{this.props.Nome}</Text>
          </View>
        </ImageBackground>
      </TouchableOpacity>
    );
  }
  

  listTres() {
    const vglobais = new VariaveisGlobais();
    const image = { uri: vglobais.getHostIMG + '/gp/' + this.props.Imagem };
    return (
      <TouchableOpacity style={{
        width: '100%', height: 165,
        borderRadius: 20,
        borderWidth: 1,
        borderColor: '#fff',
        marginBottom: 10
      }} onPress={() => {
        this.props.acao()
      }}>
        <ImageBackground source={image} style={{ flex: 1, resizeMode: "cover", justifyContent: "center" }}>
          <View style={{
            width: '100%', flex: 1, backgroundColor: "rgba(0, 0, 0, 0.40)",
            borderRadius: 20,
            borderWidth: 1,
            borderColor: '#fff',
            flexDirection: 'row',
            alignItems: 'center',
            paddingLeft: 20,
            paddingRight: 20
          }}>
            <Text style={{ fontSize: 17, width: '100%', textAlign: 'center', fontWeight: 'bold', color: '#fff' }}>{this.props.Nome}</Text>
          </View>
        </ImageBackground>
      </TouchableOpacity>
    );
  }
  
}
