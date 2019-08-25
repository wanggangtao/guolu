ALTER TABLE `boiler_guolu_attr` ADD `guolu_min_flow` INT(10) NULL COMMENT '额定功率下最小燃气流量' , ADD `guolu_max_flow` INT(10) NULL COMMENT '额定功率下最大燃气流量' , ADD `guolu_heatout_60` INT(10) NULL COMMENT '最大负荷80℃~60℃热输出' , ADD `guolu_heatout_30` INT(10) NULL COMMENT '最大负荷50℃~30℃热输出' , ADD `guolu_heatout_range` VARCHAR(100) NULL COMMENT '热输入调节范围' , ADD `guolu_heateffi_80` INT(10) NULL COMMENT '最大负荷80℃~60℃热效率%' , ADD `guolu_heateffi_50` INT(10) NULL COMMENT '最大负荷50℃~30℃热效率%' , ADD `guolu_heateffi_30` INT(10) NULL COMMENT '30%负荷50℃~30℃热效率%' ;
ALTER TABLE `boiler_guolu_attr` ADD `guolu_syswater_pre` VARCHAR(100) NULL COMMENT '最低/最高系统水压bar' , ADD `guolu_heat_capacity` INT(10) NULL COMMENT '供热水能力m3/h' ;

ALTER TABLE `boiler_guolu_attr` ADD `guolu_fluegas_80` VARCHAR(100) NULL COMMENT '烟气温度（最大负荷80℃~60℃）℃' , ADD `guolu_fluegas_50` VARCHAR(100) NULL COMMENT '烟气温度（最大负荷50℃~30℃）℃' , ADD `guolu_emission_co` VARCHAR(100) NULL COMMENT 'CO排放' , ADD `guolu_emission_nox` VARCHAR(100) NULL COMMENT 'NOx排放' , ADD `guolu_condensed_max` INT(10) NULL COMMENT '最大冷凝水排量' ;

ALTER TABLE `boiler_guolu_attr` ADD `guolu_condensed_ph` INT(10) NULL COMMENT '冷凝水PH值' , ADD `guolu_flue_interface` INT(10) NULL COMMENT '烟道接口φ mm' , ADD `guolu_gas_interface` VARCHAR(100) NULL COMMENT '燃气接口' , ADD `guolu_iowater_interface` VARCHAR(100) NULL COMMENT '进出水接口' , ADD `guolu_gas_type` VARCHAR(100) NULL COMMENT '燃气类型' , ADD `guolu_gas_press` INT NULL COMMENT '额定燃气压力(动压)Pa' , ADD `guolu_gaspre_range` VARCHAR(100) NULL COMMENT '燃气工作压力范围(动压)Pa' , ADD `guolu_energy_level` VARCHAR(100) NULL COMMENT '能效等级' , ADD `guolu_air_filter` VARCHAR(100) NULL COMMENT '空气过滤器' ;

ALTER TABLE `boiler_guolu_attr` ADD `guolu_net_weight` INT(10) NULL COMMENT '重量（净）' , ADD `guolu_refer_heatarea` INT(10) NULL COMMENT '参考供热面积' , ADD `guolu_power_supply` VARCHAR(100) NULL COMMENT '电源' , ADD `guolu_noise` VARCHAR(100) NULL COMMENT '噪音' , ADD `guolu_electric_power` INT(10) NULL COMMENT '电功率' ;

ALTER TABLE `boiler_guolu_attr` CHANGE `guolu_min_flow` `guolu_min_flow` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '额定功率下最小燃气流量', CHANGE `guolu_max_flow` `guolu_max_flow` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '额定功率下最大燃气流量', CHANGE `guolu_heatout_60` `guolu_heatout_60` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '最大负荷80℃~60℃热输出', CHANGE `guolu_heatout_30` `guolu_heatout_30` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '最大负荷50℃~30℃热输出', CHANGE `guolu_heateffi_80` `guolu_heateffi_80` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '最大负荷80℃~60℃热效率%', CHANGE `guolu_heateffi_50` `guolu_heateffi_50` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '最大负荷50℃~30℃热效率%', CHANGE `guolu_heateffi_30` `guolu_heateffi_30` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '30%负荷50℃~30℃热效率%';

ALTER TABLE `boiler_guolu_attr` CHANGE `guolu_heat_capacity` `guolu_heat_capacity` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '供热水能力m³/h', CHANGE `guolu_condensed_ph` `guolu_condensed_ph` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '冷凝水PH值';

ALTER TABLE `boiler_pipeline_attr` CHANGE `pipeline_flow` `pipeline_flow_min` INT(11) NULL DEFAULT NULL COMMENT '流量最小值';
ALTER TABLE `boiler_pipeline_attr` ADD `pipeline_flow_mid` INT(10) NULL COMMENT '流量中值' AFTER `pipeline_flow_min`, ADD `pipeline_flow_max` INT(10) NULL COMMENT '流量最大值' AFTER `pipeline_flow_mid`;
ALTER TABLE `boiler_pipeline_attr` CHANGE `pipeline_lift` `pipeline_lift_min` INT(11) NULL COMMENT '扬程最小值';
ALTER TABLE `boiler_pipeline_attr` ADD `pipeline_lift_mid` INT(10) NULL COMMENT '扬程中值' AFTER `pipeline_lift_min`, ADD `pipeline_lift_max` INT(10) NULL COMMENT '扬程最大值' AFTER `pipeline_lift_mid`;
ALTER TABLE `boiler_syswater_pump_attr` CHANGE `pump_flow` `pump_flow_min` INT(11) NULL DEFAULT NULL COMMENT '流量最小值';
ALTER TABLE `boiler_syswater_pump_attr` ADD `pump_flow_mid` INT(10) NULL COMMENT '流量中值' AFTER `pump_flow_min`, ADD `pump_flow_max` INT(10) NULL COMMENT '流量最大值' AFTER `pump_flow_mid`;
ALTER TABLE `boiler_syswater_pump_attr` CHANGE `pump_lift` `pump_lift_min` INT(11) NULL COMMENT '扬程最小值';
ALTER TABLE `boiler_syswater_pump_attr` ADD `pump_lift_mid` INT(10) NULL COMMENT '扬程中值' AFTER `pump_lift_min`, ADD `pump_lift_max` INT(10) NULL COMMENT '扬程最大值' AFTER `pump_lift_mid`;
