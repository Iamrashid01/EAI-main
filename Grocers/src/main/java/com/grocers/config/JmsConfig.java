package com.grocers.config;

import jakarta.jms.ConnectionFactory;
import org.apache.activemq.artemis.jms.client.ActiveMQConnectionFactory;
import org.apache.activemq.artemis.core.config.impl.ConfigurationImpl;
import org.apache.activemq.artemis.core.server.embedded.EmbeddedActiveMQ;
import org.springframework.context.annotation.Bean;
import org.springframework.context.annotation.Configuration;

@Configuration
public class JmsConfig {

    @Bean
    public EmbeddedActiveMQ embeddedActiveMQ() throws Exception {
        org.apache.activemq.artemis.core.config.Configuration config = new ConfigurationImpl()
            .setPersistenceEnabled(false)
            .setJournalDirectory("target/data/journal")
            .setSecurityEnabled(false)
            .addAcceptorConfiguration("tcp", "tcp://localhost:61616");

        EmbeddedActiveMQ embeddedBroker = new EmbeddedActiveMQ();
        embeddedBroker.setConfiguration(config);
        embeddedBroker.start();
        return embeddedBroker;
    }

    @Bean
    public ConnectionFactory connectionFactory() {
        return new ActiveMQConnectionFactory("tcp://localhost:61616");
    }
}