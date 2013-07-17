# -*- mode: ruby -*-
# vi: set ft=ruby :

def Kernel.is_windows?
    # Detect if we are running on Windows
    processor, platform, *rest = RUBY_PLATFORM.split("-")
    platform == 'mingw32'
end

Vagrant::Config.run do |config|
  # Define VM box to use
  config.vm.box = "precise32"
  config.vm.box_url = "http://files.vagrantup.com/precise32.box"

  # Increase memory of the VM
  config.vm.customize ["modifyvm", :id, "--memory", 512]

  # Define hostname to be used with Hostmaster
  config.vm.host_name = "l4queues.local"
  config.hosts.name = "l4queues.local"

  # Use hostonly network with a static IP Address
  config.vm.network :hostonly, "172.90.90.42"

  # Set share folder
  use_nfs = !Kernel.is_windows?
  config.vm.share_folder "shared" , "/home/vagrant/shared", "./", :nfs => use_nfs

  # Enable and configure chef solo
  config.vm.provision :chef_solo do |chef|
    chef.cookbooks_path = ["cookbooks"]
    chef.add_recipe "apt"
    chef.add_recipe "postfix"
    chef.add_recipe "openssl"
    chef.add_recipe "apache2"
    chef.add_recipe "apache2::mod_php5"
    chef.add_recipe "apache2::mod_rewrite"
    chef.add_recipe "apache2::mod_ssl"
    chef.add_recipe "mysql"
    chef.add_recipe "mysql::server"
    chef.add_recipe "memcached"
    chef.add_recipe "misc::packages"
    chef.add_recipe "misc::vhost"
    chef.add_recipe "misc::db"
    chef.add_recipe "dotdeb"
    chef.add_recipe "dotdeb::php54"
    chef.add_recipe "php"
    chef.add_recipe "beanstalkd"
    chef.json = {
      :misc => {
        # Project name
        :name           => "l4queues",

        # Name of MySQL database that should be created
        :db_name        => "l4queues",

        # Optional database dump to be imported when server is provisioned
        # If the file doesn't exist, it is just ignored
        :db_dump        => "/home/vagrant/shared/dump.sql",

        # Server name and alias(es) for Apache vhost
        :server_name    => "l4queues.local",
        :server_aliases => "*.l4queues.local",

        # Document root for Apache vhost
        :docroot        => "/home/vagrant/shared/public",
      },
      :mysql => {
        :server_root_password   => 'root',
        :server_repl_password   => 'root',
        :server_debian_password => 'root',
        :bind_address           => '172.90.90.42',
        :allow_remote_root      => true
      }
    }
  end
end