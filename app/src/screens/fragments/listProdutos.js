import React, { Component } from 'react';
import { Container, Header, Content, List, ListItem, Left, Body, Right, Thumbnail, Text } from 'native-base';
import VariaveisGlobais from './../../utilites/variaveisDoSistema';
import { View } from 'react-native';
import Image from 'react-native-image-progress';
import { Progress } from 'react-native-progress';

// import { Container } from './styles';

export default class ListProdutos extends Component {
  render() {
    const vglobais = new VariaveisGlobais();
    
    return (
      <ListItem avatar style={{ marginLeft: 0, marginRight: 10 }} onPress={() => {
        this.props.acao()
      }}>
        <Left>
          <Image
            source={{ uri: vglobais.getHostIMG + '/pod/' + this.props.Imagem }}
            indicator={Progress}
            indicatorProps={{
              size: 35,
              borderWidth: 0,
              color: 'rgba(150, 150, 150, 1)',
              unfilledColor: 'rgba(200, 200, 200, 0.2)'
            }}
            style={{
              marginTop: 0,
              width: 60,
              height: 60,
              backgroundColor: '#d8dadc',
              position: 'relative',
            }}>
          </Image>
        </Left>
        <Body>
          <Text numberOfLines={3}>{this.props.Nome}</Text>
          <Text note numberOfLines={2} style={{ }}>{this.props.Obs}</Text>
          <Text style={{ fontSize: 15, fontWeight: 'bold' }}>R$ {this.mascaraValor(eval(parseFloat(this.props.Preco)).toFixed(2))}</Text>
        </Body>
      </ListItem>
    );
  }

  mascaraValor(valor) {
    valor = valor.toString().replace(/\D/g, "");
    valor = valor.toString().replace(/(\d)(\d{8})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{5})$/, "$1.$2");
    valor = valor.toString().replace(/(\d)(\d{2})$/, "$1,$2");
    return valor
  }


}
